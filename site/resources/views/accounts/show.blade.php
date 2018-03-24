@extends('layouts.app')

@section('content')
    <h3>Account Record View </h3>

    <div class="accounts record">
        <div class="account-name">

            <label for="name">Account: </label>
            <span class="name" id="name" name="name">{{ $account }}</span>
        </div>
    </div>


@endsection
