@extends('layouts.app')

@section('content')
    <h3>User Record View </h3>

    <div class="users record">
        <div class="user-name">

            <label for="name">User: </label>
            <span class="name" id="name" name="name">{{ $user }}</span>
        </div>
    </div>


@endsection
