@extends('_layouts.logged_in')

@section('content')
<div class="container">
    @include('dashboard.dashboard_' . $current_user->user_type)
</div>
@endsection
