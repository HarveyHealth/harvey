@extends('_layouts.logged_in')
@section('page_title','User List')

@push('stylesheets')
@endpush

@push('scripts')
@endpush




@section('content')

<div class="container">
    <h2>Users</h2>
</div>

@if (count($users) > 0)
<table>
    @foreach ($users as $user)
        <tr>
            <td><a href="/users/{{ $user->id }}">{{ $user->id }}</a></td>
            <td>{{ $user->first_name }}</td>
            <td>{{ $user->last_name }}</td>
            <td>{{ $user->email }}</td>
        </tr>
    @endforeach
</table>

@else

<p>No users, sorry.</p>

@endif

@endsection
