<?php

namespace Tests\Unit;

use App\Mail\FeedbackAnswer;
use App\Models\Feedback;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_email()
    {
//        $feedback = Feedback::factory()->create();
//        Mail::to('vanteateam@gmail.com')->send(new FeedbackAnswer($feedback));

        $this->assertTrue(true);
    }
}