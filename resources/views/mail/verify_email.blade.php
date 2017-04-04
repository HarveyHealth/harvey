@component('mail::message')

# Hello, {{ $mailable->first_name }}!

Welcome to Harvey. Please verify your email address by clicking the link below.

@component('mail::button', ['url' => $mailable->emailVerificationURL()])
Verify Email
@endcomponent

Thank you,<br>
{{ config('app.name') }}


<!-- Subcopy -->
@component('mail::subcopy')
If you’re having trouble clicking the "Verify Email" button, copy and paste the URL below into your web browser: [{{ $mailable->emailVerificationURL() }}]({{ $mailable->emailVerificationURL() }})
@endcomponent

@endcomponent
