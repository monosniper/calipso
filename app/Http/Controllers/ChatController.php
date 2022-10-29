<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConversationRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\User;
use Chat;
use Illuminate\Support\Collection;
use Musonza\Chat\Exceptions\DirectMessagingExistsException;
use Musonza\Chat\Models\Conversation;

class ChatController extends Controller
{
    use AuthUserWithCountsController;

    public function getCompanion($conversation) {
        return $conversation->participants()->where('messageable_id', '!=', auth()->id())->first()->messageable;
    }

    public function index() {
        $user = $this->getAuthUserWithCounts();

        $participations = Chat::conversations()->setPaginationParams(['sorting' => 'desc'])
            ->setParticipant(auth()->user())
            ->isDirect()
            ->get();

        $conversations = $participations->map(function($participation) {
            $conversation = $participation->conversation->toArray();

            $conversation['companion'] = $this->getCompanion($participation->conversation);

            if($participation->conversation->last_message) {
                $conversation['last_message']['created_at_human'] = $participation->conversation->last_message->created_at->isoFormat('hh:mm, D MMMM');
            }

            return $conversation;
        });

        return view('dashboard.chat.index', [
            'conversations' => $conversations->paginate(12),
            'user' => $user,
        ]);
    }

    public function conversation(Conversation $conversation) {
        $user = $this->getAuthUserWithCounts();
        $companion = $this->getCompanion($conversation);

        return view('dashboard.chat.conversation', [
            'conversation' => $conversation,
            'companion' => $companion,
            'user' => $user,
        ]);
    }

    public function addConversation($companion_id) {
        $companion = User::findOrFail($companion_id);
        $participants = [auth()->user(), $companion];

        try {
            $conversation = Chat::createConversation($participants)->makeDirect();
        } catch (DirectMessagingExistsException $err) {
            $conversation = Chat::conversations()->between(...$participants);
        }

        return redirect()->route('chat.conversation', $conversation->id);
    }

    public function addMessage(StoreMessageRequest $request) {
        $conversation = Chat::conversations()->getById($request->conversation_id);

        $message = Chat::message($request->message)
            ->from(auth()->user())
            ->to($conversation)
            ->send();

        return redirect()-> route('chat.conversation', $conversation->id);
    }
}
