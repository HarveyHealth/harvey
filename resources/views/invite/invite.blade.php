@extends('_layouts.logged_in')
@section('page_title','Invite a User')



@section('content')
<h1>Invite a User</h1>
<form method="post" action="/invite" class="control">

    {{ csrf_field() }}
    <label class="label" for="first_name">First Name</label>
    <input type="text" value="{{ old('first_name') }}" name="first_name" id="first_name" required />

    <label class="label" for="last_name">Last Name</label>
    <input type="text" value="{{ old('last_name') }}" name="last_name" id="last_name" required />

    <label class="label" for="email">Email Address</label>
    <input type="text" value="{{ old('email') }}" name="email" name="id" required />

    <label class="label" for="user_type">User Type</label>
    <select name="user_type" required>
            <option value="admin">Admin</option>
            <option value="practitioner">Practitioner</option>
    </select>
    
    <input type="submit" class="button is-primary" value="Invite User" />
</form>

@endsection
