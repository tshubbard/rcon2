<template>
    <div class="dashboard">
        <div class="container-fluid">
            <div class="row dashboard-summary">
                <div class="col-md-5" v-if="servers.length === 1">
                    Server: {{servers[0].name}}
                </div>
                <div class="col-md-5" v-if="servers.length > 1">
                    Servers:
                    <select class="server-select-dropdown" v-on:change="updateSelectedServer">
                        <option v-for="row in servers" :value="row.id">{{row.name}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="dashboard-panel col-md-6">
                                <div class="panel-top">
                                    <div class="md-hue-2">
                                        <div class="md-toolbar-tools">
                                            <span class="server-actions float-right">
                                                <i class="material-icons clickable" aria-hidden="true"
                                                   v-on:click="showAddEditServerEvent()">add_box</i>
                                            </span>
                                            <h2 flex>Server Events</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <ul class="server-events">
                                        <li v-for="event in server_selected.events" class="server-event">
                                            <md-switch v-model="event.is_active"
                                                       v-on:change="toggleEventActive(event)"
                                                    aria-label="Server Event Active on/off toggle"></md-switch>
                                            <span class="server-event-name">{{event.name}}</span>
                                            <span class="server-actions float-right">
                                                <i class="material-icons clickable"
                                                   v-on:click="showAddEditServerEvent(event)"
                                                   aria-hidden="true">mode_edit</i>
                                                <i class="material-icons clickable"
                                                   v-on:click="removeServerEvent(event)"
                                                   aria-hidden="true">delete</i>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="dashboard-panel col-md-6">
                                <div class="panel-top">
                                    <div class="md-hue-2">
                                        <div class="md-toolbar-tools">
                                            <h2 flex md-truncate>Active Users</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <p>User List Here</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {HTTP} from '../app';

    export default {
        data: function(){
            return {
                servers: [],
                server_selected: {
                    events: []
                }
            }
        },
        created: function() {
            let serverId = +this.$route.params.serverId;

            HTTP.get('/api/v1/user/servers')
                .then(response => {
                    this.servers = response.data;

                    // add any data parsing we need
                    _.each(this.servers, function(server) {
                        _.each(server.events, function(event) {
                            event.is_active = !!event.is_active;
                        }, this);
                    }, this);

                    if (serverId) {
                        this.server_selected = _.find(this.servers, function(server) {
                            return server.id === serverId;
                        })
                    } else {
                        if(this.servers.length > 0) {
                            this.server_selected = this.servers[0];
                        }
                    }
                    console.log('user/servers data ', response);
                    console.log(this.server_selected);
                })
                .catch(e => {
                    this.errors.push(e)
                });
        },
        methods: {
            updateSelectedServer: function(e) {
                this.server_selected = this.servers[e.target.value];
            },
            showAddEditServerEvent: function(event) {
                console.log('showAddEditServerEvent ', event);
            },
            toggleEventActive: function(event) {
                let active = event.is_active ? 1 : 0;
                HTTP.put('/api/v1/serverEvent/' + event.id, {
                        is_active: active
                    })
                    .then(response => {
                        console.log('response.data ', response.data);
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            removeServerEvent: function(event) {
                console.log('removeServerEvent ', event);
            }
        },
        computed: {
            eventClass: function(event){
                return 'server-event my-2 ' + (event.is_active ? '' : 'not-') + 'active';
            }
        }
    }
</script>
