@extends('layouts.app-admin')

@section('admin-content')

    <h3>Admin Dashboard</h3>

    <div>Hello {{ $thisUser->name }} - {{ $thisUser->role->name }}</div>

@endsection
