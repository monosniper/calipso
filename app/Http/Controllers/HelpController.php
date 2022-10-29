<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index() {
        $questions = Question::all();
        return view('help.index', [
            'questions' => $questions,
        ]);
    }

    public function company() {
        return view('help.company');
    }

    public function shop() {
        return view('help.shop');
    }

    public function freelance() {
        return view('help.freelance');
    }

    public function policy() {
        return view('help.policy');
    }

    public function conditions() {
        return view('help.conditions');
    }

    public function sitemap() {
        return view('help.sitemap');
    }
}
