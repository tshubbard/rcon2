<template>
    <div class="users record">
        <h3 class="title">
            Users Management
        </h3>

        <div class="container-fluid">
            <div class="row record-body">
                <table class="admin-users-table table">
                    <thead>
                    <th class="text-center">ID</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Role</th>
                    <th class="text-center">Actions</th>
                    </thead>
                    <tbody>
                        <tr v-for="user in users">
                            <td class="text-center">{{user.id}}</td>
                            <td>
                                <router-link class="nav-link" :to="'/u/' + user.name">
                                    {{user.name}}
                                </router-link>
                            </td>
                            <td>{{user.email}}</td>
                            <td class="text-center">{{user.role_id}}</td>
                            <td>
                                <div v-if="thisUser.role_id === 4">
                                    <a class="user-action" :href="'/admin/users/' + user.id + '/delete'">
                                        Delete
                                    </a>
                                    <a class="user-action" :href="'/admin/users/' + user.id + '/remAdmin'">
                                        -Admin
                                    </a>
                                    <a class="user-action" :href="'/admin/users/' + user.id + '/addAdmin'"
                                       v-if="user.role_id === 3">
                                        +Admin
                                    </a>
                                    <a class="user-action" :href="'/admin/users/' + user.id + '/remMod'"
                                       v-if="user.role_id === 3">
                                        -Mod
                                    </a>
                                    <a class="user-action" :href="'/admin/users/' + user.id + '/addMod'"
                                       v-if="user.role_id < 3">
                                        +Mod
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function() {
            return {
                thisUser: {},
                users: []
            }
        },
        created: function() {
            $.get('/api/v1/admin/users', function(data) {
                console.log('data: ', data);

                this.thisUser = data.thisUser;
                this.users = data.users;

            }.bind(this), 'json');
        },
        methods: {
        },
        computed: {
        }
    }
</script>
