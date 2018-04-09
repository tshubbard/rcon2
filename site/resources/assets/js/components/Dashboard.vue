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
                                                   @click.stop="showAddEditServerEvent()">add_box</i>
                                            </span>
                                            <h2 flex>Server Events</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <ul class="server-events">
                                        <li v-for="event in selectedServer.events" class="server-event">
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

        <md-dialog-confirm
                :md-active.sync="showDeleteServerEvent"
                md-title="Delete Server Event "
                v-model="selectedDeleteEvent"
                md-content="Are you sure you want to delete the event?"
                md-confirm-text="Delete Event"
                md-cancel-text="Cancel"
                @md-cancel="onDeleteServerEventCancel"
                @md-confirm="onDeleteServerEventConfirm"
        />

        <server-event-add-edit
                :visible="showServerEventAddEdit"
                :data="selectedServerEvent"
                :items="rustItems"
                @close="showServerEventAddEdit = false">

        </server-event-add-edit>
    </div>
</template>

<script>
    import {HTTP} from '../app';
    import ServerEventAddEdit from './ServerEventAddEdit';

    export default {
        components: {
            ServerEventAddEdit
        },
        data: function() {
            return {
                errors: [],
                rustItems: [],
                servers: [],
                selectedServer: {
                    events: []
                },
                showServerEventAddEdit: false,
                selectedServerEvent: {
                    actionType: 'Add'
                },
                showDeleteServerEvent: false,
                selectedDeleteEvent: {
                    name: ''
                }
            }
        },
        created: function() {
            let serverId = +this.$route.params.serverId;
            let items = localStorage.getItem('rustItems');

            HTTP.get('/api/v1/user/servers')
                .then(response => {
                    this.servers = response.data;

                    // add any data parsing we need
                    this.servers.forEach(_.bind(function(server) {
                        server.events.forEach(_.bind(function(event) {
                            event.is_active = !!event.is_active;
                            event.is_public = !!event.is_public;
                            event.commands = JSON.parse(event.commands);
                            this.updateEventTimer(event);
                        }, this));
                    }, this));

                    if (serverId) {
                        this.selectedServer = _.find(this.servers, function(server) {
                            return server.id === serverId;
                        })
                    } else {
                        if(this.servers.length > 0) {
                            this.selectedServer = this.servers[0];
                        }
                    }
                    console.log('user/servers data ', response);
                    console.log('this.servers ', this.servers);
                    console.log('selectedServer: ', this.selectedServer);
                })
                .catch(e => {
                    console.log('/api/v1/user/servers error ', e);

                    this.errors.push(e)
                });

            // todo: set up a latest items ID/hash/timestamp so we dont get this every time
            //if (!items) {
                HTTP.get('/api/v1/items')
                    .then(response => {
                        console.log('ITEMS data: ', response.data);
                        this.rustItems = response.data.items;
                        localStorage.setItem('rustItems', JSON.stringify(this.rustItems));
                    })
                    .catch(e => {
                        console.log('/api/v1/items error ', e);

                        this.errors.push(e)
                    });
            //}
        },
        methods: {
            /**
             * Converts interval time to days/hours/minutes
             */
            updateEventTimer: function(event) {
                let days;
                let hours;
                let totalTime = event.command_timer;

                event.command_interval_days = 0;
                event.command_interval_hours = 0;
                event.command_interval_minutes = 5;

                if (totalTime) {
                    days = totalTime / 1440;

                    if (days >= 1) {
                        days = Math.floor(days);
                        event.command_interval_days = days;
                        totalTime -= (days * 1440);
                    }

                    hours = totalTime / 60;

                    if (hours >= 1) {
                        hours = Math.floor(hours);
                        event.command_interval_hours = hours;
                        totalTime -= (hours * 60);
                    }

                    event.command_interval_minutes = totalTime;
                }
            },

            updateSelectedServer: function(e) {
                this.selectedServer = this.servers[e.target.value];
            },

            /**
             * Toggles the Add/Edit Server Event modal
             */
            showAddEditServerEvent: function(event) {
                if (event) {
                    this.selectedServerEvent = _.clone(event);
                    this.selectedServerEvent.actionType = 'Edit';
                } else {
                    this.selectedServerEvent = {
                        actionType: 'Add',
                        host: undefined,
                        max_players: undefined,
                        name: undefined,
                        password: undefined,
                        port: undefined
                    };
                }

                this.showServerEventAddEdit = !this.showServerEventAddEdit;
                console.log('showAddEditServerEvent ', this.selectedServerEvent, this.showServerEventAddEdit);
            },

            /**
             * Toggles a server event active or not
             *
             * @param {Object} event The ServerEvent object to toggle
             */
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
                this.selectedDeleteEvent = event;
                this.showDeleteServerEvent = true;
                console.log('removeServerEvent ', event);
            },
            onDeleteServerEventConfirm: function(event) {
                this.selectedDeleteEvent = event;
                console.log('onDeleteServerEventConfirm ', event);
            },
            onDeleteServerEventCancel: function() {
                this.showDeleteServerEvent = false;
                console.log('onDeleteServerEventCancel ', event);

            },
        },
        computed: {
            eventClass: function(event){
                return 'server-event my-2 ' + (event.is_active ? '' : 'not-') + 'active';
            }
        }
    }
</script>
