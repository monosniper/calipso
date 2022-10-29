@component('mail::message')
# {{__('mail.feedback.title', ['theme' => $feedback->theme])}}

{{$feedback->answer}}
@endcomponent
