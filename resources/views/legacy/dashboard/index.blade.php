@extends('legacy._layouts.logged_in')

@section('content')
<div class="container">
    @include('legacy.dashboard.dashboard_' . $current_user->userType())
</div>
@endsection
