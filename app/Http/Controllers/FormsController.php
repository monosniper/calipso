<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\StorePortfolioRequest;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateAvatarRequest;
use App\Http\Requests\UpdateResumeRequest;
use App\Http\Requests\UpdateSafeResultRequest;
use App\Http\Requests\UpdateSafeTzRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Category;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\Portfolio;
use App\Models\Report;
use App\Models\Safe;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class FormsController extends Controller
{
    public function feedback(StoreFeedbackRequest $request) {
        $feedback = new Feedback();

        $feedback->email = $request->email;
        $feedback->theme = $request->theme;
        $feedback->content = $request->input('content');

        $feedback->save();

        return back()->with('success', __('messages.feedback'));
    }

    public function resume(UpdateResumeRequest $request) {
        $user = auth()->user();

        $user->update(['resume' => $request->input('content')]);
        $user->save();

        return back()->with('success', 'Резюме обновлено успешно');
    }

    public function profile(UpdateUserRequest $request) {
        $user = auth()->user();

        $user->update($request->validated());
        $user->save();

        if($request->filled('categories')) {
            $user->categories()->detach();
            $user->categories()->attach($request->categories);
        }

        return back()->with('success', 'Профиль обновлён успешно');
    }

    public function avatar(UpdateAvatarRequest $request) {
        $user = User::findOrFail($request->user_id);
        $user->addMedia($request->file('avatar'))->toMediaCollection('avatar');

        return back()->with('success', 'Профиль обновлён успешно');
    }

    public function filepond_avatar(Request $request) {
        dd($request->all());
        return auth()->user()->getFirstMedia('avatar');
        if($request->isMethod('post')) {
            if($request->hasFile('avatar')) {
                auth()->user()->addMedia($request->file('avatar'))->toMediaCollection('avatar');

                return true;
            }

            return auth()->user()->getFirstMedia('avatar');
        }

        return auth()->user()->getFirstMedia('avatar');
    }

    public function safe(UpdateSafeTzRequest $request) {
        $safe = Safe::findOrFail($request->safe_id);

        if(!Order::where([
            ['user_id', auth()->id()],
            ['id', $safe->order_id],
        ])->exists()) abort(403);

        if($request->has('tz')) {
            $safe->tz = $request->tz;
            $safe->save();
        }

        if ($request->has('files')) {
            foreach ($request->file('files', []) as $key => $file) {
                $safe->addMedia($file)->preservingOriginal()->toMediaCollection('files');
            }
        }

        return back()->with('success', __('safe.success.tz_update'));
    }

    public function safeResult(UpdateSafeResultRequest $request) {
        $safe = Safe::findOrFail($request->safe_id);

        if(!Order::where([
            ['freelancer_id', auth()->id()],
            ['id', $safe->order_id],
        ])->exists()) abort(403);

        $safe->result_link = $request->result_link;
        $safe->save();

        return back()->with('success', __('safe.success.result_url_update'));
    }

    public function report(StoreReportRequest $request) {
        Report::create($request->validated());
        return back()->with('success', __('messages.thanks_for_report'));
    }
}
