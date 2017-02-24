@extends('_layouts.public')

@section('page_title','Email Verified')

@section('content')
<section class="section">
        <div class="container">
            <div class="content">
                <h2>Thank you for verifying your email.</h2>

                @if ($password_not_set)
                <h3>Please set your password.</h3>
					<form action="/verify/{{ $user_id }}/{{ $token }}" method="POST">
                        {{ csrf_field() }}
						<label for="password">Password:</label>
						<input type="password" name="password">
						<input type="submit">
					</form>
				@endif
            </div>
        </div>
    </section>
@endsection
