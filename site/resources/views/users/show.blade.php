@extends('layouts.app')

@section('content')
    <div class="users record">
        <h3 class="title">
            <span class="name">{{ $user->name }}</span>
        </h3>

        <div class="record-body">
            <div class="user-name">

                <label for="name">User: </label>
                <span class="name" id="name" name="name">{{ $user }}</span>
            </div>
            <div class="user-name">

                <label for="name">User: </label>
                <span class="name" id="name" name="name">{{ $user->accounts }}</span>
            </div>
        </div>

    </div>
@endsection
