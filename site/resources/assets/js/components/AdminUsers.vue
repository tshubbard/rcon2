<template>
    <div class="admin users m-3 record">
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
                        <tr v-for="user in users" :key="user.id">
                            <td class="text-center">{{user.id}}</td>
                            <td>
                                <router-link class="nav-link" :to="'/u/' + user.name">
                                    {{user.name}}
                                </router-link>
                            </td>
                            <td>{{user.email}}</td>
                            <td class="text-center">{{user.role_id}}</td>
                            <td>
                                <div v-if="thisUser.role_id === 4" class="user-action">
                                    <md-button class="md-icon-button md-raised"
                                               @click.stop="deleteUser(user)">
                                        <i class="material-icons clickable" aria-hidden="true" v-cloak>delete_forever</i>
                                    </md-button>
                                    <md-button class="md-raised md-text-icon-button"
                                               @click.stop="changeRoleId(user, false)"
                                               v-if="user.role_id === 4">
                                        Admin
                                        <i class="material-icons clickable" aria-hidden="true" v-cloak>arrow_drop_down</i>
                                    </md-button>
                                    <md-button class="md-raised md-text-icon-button"
                                               @click.stop="changeRoleId(user, true)"
                                               v-if="user.role_id === 3">
                                        Admin
                                        <i class="material-icons clickable" aria-hidden="true" v-cloak>arrow_drop_up</i>
                                    </md-button>
                                    <md-button class="md-raised md-text-icon-button"
                                               @click.stop="changeRoleId(user, false)"
                                               v-if="user.role_id === 3">
                                        Mod
                                        <i class="material-icons clickable" aria-hidden="true" v-cloak>arrow_drop_down</i>
                                    </md-button>
                                    <md-button class="md-raised md-text-icon-button"
                                               @click.stop="changeRoleId(user, true)"
                                               v-if="user.role_id < 3">
                                        Mod
                                        <i class="material-icons clickable" aria-hidden="true" v-cloak>arrow_drop_up</i>
                                    </md-button>
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
    import {HTTP} from '../app';

    export default {
        data: function() {
            return {
                errors: [],
                thisUser: {},
                users: []
            }
        },
        created: function() {
            let url = HTTP.buildUrl('admin/users');
            HTTP.get(url)
                .then(response => {
                    console.log('response: ', response);
                    this.thisUser = response.data.thisUser;
                    this.users = response.data.users;
                })
                .catch(e => {
                    HTTP.logError(url, e);
                    this.errors.push(e)
                });
        },
        methods: {
            deleteUser: function(user) {
                let url = HTTP.buildUrl('admin/users/' + user.id);
                HTTP.delete(url)
                    .then(response => {
                        this.users = _.reject(this.users, user);
                    })
                    .catch(e => {
                        HTTP.logError(url, e);
                        this.errors.push(e)
                    });
            },

            changeRoleId: function(user, isPromotion) {
                let roleId = +user.role_id;
                let url = HTTP.buildUrl('admin/users/' + user.id);

                if (isPromotion) {
                    roleId++;
                } else {
                    roleId--;
                }

                HTTP.put(url, {
                        role_id: roleId
                    })
                    .then(_.bind(response => {
                        this.users.forEach(function(u, index) {
                            if (u.id === user.id) {
                                this.users[index].role_id = response.data.data.role_id;
                            }
                        }, this);
                    }, this))
                    .catch(e => {
                        HTTP.logError(url, e);
                        this.errors.push(e)
                    });

            }
        },
        watch: {
        },
        computed: {
        }
    }
</script>
