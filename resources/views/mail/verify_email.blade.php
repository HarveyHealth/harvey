Hello, {{ $mailable->first_name }}!

Please veryify your email address by clicking the link below. If clicking it does not work, you may need to copy and paste it into your browser's URL bar.

{{ $mailable->emailVerificationURL() }}

Thank you!
