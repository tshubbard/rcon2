<template>
    <div class="dashboard">
        <md-content>

        <div class="container-fluid">
            <div class="row dashboard-summary">
                <div class="col-md-5" v-if="servers.length === 1">
                    Server: {{servers[0].name}}
                </div>
                <div class="col-md-5" v-if="servers.length > 1">
                    <md-field>
                        <label for="selected-server">Servers:</label>
                        <md-select id="selected-server" name="selected-server"
                                   v-model="selectedServerId"
                                   @input="updateSelectedServer">
                            <md-option v-for="server in servers"
                                       :key="server.id"
                                       :value="server.id">
                                {{server.name}}
                            </md-option>
                        </md-select>
                    </md-field>
                </div>
                <div class="col-md-2 add-edit-server-buttons">
                    <span class="server-actions" v-if="servers.length">
                        <i class="material-icons clickable" aria-hidden="true"
                           @click.stop="showEditServer()">edit</i>
                    </span>
                    <span class="server-actions">
                        <i class="material-icons clickable" aria-hidden="true"
                           @click.stop="showAddServer()">add_box</i>
                    </span>
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
                v-on:event-changed="onServerEventChanged"
                v-on:event-added="onServerEventAdded"
                @close="showServerEventAddEdit = false">

        </server-event-add-edit>
        <server-add-edit
                :visible="showServerAddEdit"
                :serverData="selectedServerForAddEdit"
                v-on:server-change="onServerChange"
                @close="showServerAddEdit = false">

        </server-add-edit>

        </md-content>

    </div>
</template>

<script>
    import {HTTP} from '../app';
    import {Utils} from '../utils';
    import ServerEventAddEdit from './ServerEventAddEdit';
    import ServerAddEdit from './ServerAddEdit';

    export default {
        components: {
            ServerEventAddEdit,
            ServerAddEdit
        },
        data: function() {
            return {
                errors: [],
                rustItems: [],
                servers: [],
                selectedServerId: -1,
                selectedServer: {
                    events: []
                },
                selectedServerForAddEdit: {
                    events: []
                },
                showServerAddEdit: false,
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
                            event.is_indefinite = !!event.is_indefinite;
                            event.is_public = !!event.is_public;
                            event.commands = JSON.parse(event.commands);
                            Utils.updateEventTimer(event);
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

                    this.selectedServerId = this.selectedServer.id;

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
             * Updates the selected server when the dropdown is changed
             */
            updateSelectedServer: function(serverId) {
                this.selectedServer = _.find(this.servers, function(server) {
                    return server.id === serverId;
                })
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
                        name: undefined,
                        server_id: this.selectedServer.id,
                        command_interval_days: 0,
                        command_interval_hours: 0,
                        command_interval_minutes: 5,
                        command_trigger: undefined,
                        command_timer: undefined,
                        event_type: 'timer',
                        commands: [],
                        is_public: 0,
                        is_active: 0,
                        votes: 0
                    };
                }

                this.showServerEventAddEdit = !this.showServerEventAddEdit;
                console.log('showAddEditServerEvent ', this.selectedServerEvent, this.showServerEventAddEdit);
            },

            showEditServer: function() {
                this.selectedServerForAddEdit = this.selectedServer;
                this.showServerAddEdit = !this.showServerAddEdit;
                console.log('showEditServer  ', this.showServerAddEdit);
            },

            showAddServer: function() {
                this.selectedServerForAddEdit = {
                    account_id: '',
                    disabled: 0,
                    host: '',
                    name: '',
                    password: '',
                    port: '',
                    max_players: ''
                };
                this.showServerAddEdit = !this.showServerAddEdit;

                console.log('showAddServer  ', this.showServerAddEdit);

            },

            onServerChange: function(changedServer) {
                debugger;
            },

            /**
             * Toggles a server event active or not
             *
             * @param {Object} event The ServerEvent object to toggle
             */
            toggleEventActive: function(event) {
                let active = event.is_active ? 1 : 0;
                HTTP.put('/api/v1/serverEvent/' + event.id + '/active/' + active)
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

            /**
             * When user confirms to delete an event, this makes the delete call
             */
            onDeleteServerEventConfirm: function() {
                console.log('onDeleteServerEventConfirm ', this.selectedDeleteEvent);

                HTTP.delete('/api/v1/serverEvent/' + this.selectedDeleteEvent.id)
                    .then(response => {
                        console.log('response.data ', response.data);
                        this.selectedServer.events = _.reject(this.selectedServer.events, function(evt) {
                            return +evt.id === +response.data.id;
                        });
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },

            /**
             * When user cancels a delete event, hide the delete event dialog
             */
            onDeleteServerEventCancel: function() {
                this.showDeleteServerEvent = false;
            },

            /**
             * When the user saves and updates an existing event,
             * push the changes back to the dashboard
             *
             * @param {object} changedEvent The changed/saved event
             */
            onServerEventChanged: function(changedEvent) {
                _.each(this.selectedServer.events, function(evt) {
                    if (+evt.id === changedEvent.id) {
                        evt = changedEvent;
                    }
                }, this);
            },

            /**
             * When the user creates a new event,
             * push the changes back to the dashboard
             *
             * @param {object} changedEvent The changed/saved event
             */
            onServerEventAdded: function(addedEvent) {
                this.selectedServer.events.push(addedEvent);

                debugger;

            }
        },
        computed: {
            eventClass: function(event){
                return 'server-event my-2 ' + (event.is_active ? '' : 'not-') + 'active';
            }
        }
    }
</script>
