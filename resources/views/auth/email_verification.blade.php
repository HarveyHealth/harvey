@extends('_layouts.public')

@section('page_title','Email Verified')

@section('content')
<section class="section">
        <div class="container">
            <div class="content">
                <h2>Thank you for verifying your email.</h2>
{{ $user_type }}
                @if ($password_set)
					<form action="/">
						<label for="password">Set Password:</label>
						<input type="password" name="password">
						<input type="submit">
					</form>
				@endif
            </div>
        </div>
    </section>
@endsection
