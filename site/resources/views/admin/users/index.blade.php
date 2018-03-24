@extends('layouts.app-admin')

@section('admin-content')

    <h3>Users Management</h3>

    <div>Hello {{ $thisUser->name }}</div>
    <div>
        <!-- todo: ANGULARIFY ALL THIS SHIT -->

        <table class="admin-users-table">
            <thead>
                <th class="text-center">ID</th>
                <th class="text-center">Name</th>
                <th class="text-center">Email</th>
                <th class="text-center">Role</th>
                <th class="text-center">Actions</th>
            </thead>
            <tbody>

            </tbody>
            @foreach($users as $user)
                <tr>
                    <td class="text-center">{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="text-center">
                        {{ $user->role->name }}
                    </td>
                    <td class="text-center">
                        @if($user->isAdmin())
                            <!-- todo: NEEDS TO BE DELETE REQUEST -->
                            <a class="user-action" href="/admin/users/{{$user->id}}/delete">Delete</a>

                            @if($thisUser->canChangeAdmin())
                                <a class="user-action" href="/admin/users/{{$user->id}}/remAdmin">-Admin</a>
                            @endif
                        @endif

                        @if($user->isMod() && ($thisUser->canChangeAdmin() || $thisUser->canChangeMod()))
                            <a class="user-action" href="/admin/users/{{$user->id}}/addAdmin">+Admin</a>
                            <a class="user-action" href="/admin/users/{{$user->id}}/remMod">-Mod</a>
                        @endif
                        @if(!$user->isMod() && !$user->isAdmin() &&
                                ($thisUser->canChangeAdmin() || $thisUser->canChangeMod()))
                            <a class="user-action" href="/admin/users/{{$user->id}}/addMod">+Mod</a>
                        @endif

                    </td>
                </tr>
            @endforeach
        </table>
    </div>

@endsection
