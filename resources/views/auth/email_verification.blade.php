@extends('_layouts.public')

@section('page_title','Email Verified')

@section('content')

<div class="container">
	<h2>
		{{ $verified_already ?
			'You have already verified your email.' :
			'Thank you for verifying your email.' }}
	</h2>
    <p>Visit your <a href="/dashboard">dashboard here</a>.</p>
</div>


@endsection
