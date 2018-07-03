<template>
    <div class="server m-3 record">
        <h3 class="title">
            <span class="name">{{ server.name }}</span>
        </h3>

        <errors-view :errors="errors"></errors-view>

        <div class="container-fluid record-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Description</h5>
                    <div>
                        <span class="name" id="description" name="description">
                            {{ server.description }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h5>Servers</h5>
                    <div>
                        <!--<ul>
                            <li v-for="server in servers">
                                <router-link class="nav-link" :to="'/s/' + server.slug">
                                    {{server.name}}
                                </router-link>
                            </li>
                        </ul>-->
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Users</h5>
                    <div>
                        <ul>
                           <!-- <li v-for="user in users">
                                <router-link class="nav-link" :to="'/u/' + user.name">
                                    {{user.name}}
                                </router-link>
                            </li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {HTTP} from '../app';
    import ErrorsView from './ErrorsView';

    export default {
        components: {
            ErrorsView
        },
        data: function() {
            return {
                server: {},
                errors: [],
                servers: [],
                users: []
            }
        },
        created: function() {

            HTTP.get('/api/v1/s/' + this.$route.params.serverName)
                .then(response => {
                    console.log('server data: ', response);

                    this.server = response.data;
                    this.servers = response.data.servers;
                    this.users = response.data.users;
                })
                .catch(e => {
                    console.log('/api/v1/s/' + this.$route.params.serverName + ' error ', e);
                    this.errors.push(e)
                });
        },
        methods: {
        },
        computed: {
        }
    }
</script>
