@extends('_layouts.logged_in')
@section('page_title','Profile for ' . $user->fullName())

@push('stylesheets')
@endpush

@push('scripts')
@endpush




@section('content')

<div class="container">
    <h2>{{ $user->fullName() }}</h2>
</div>

@endsection
