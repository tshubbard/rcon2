<template>
    <div class="dashboard" v-cloak>
        <md-content>

        <div class="container-fluid dashboard-summary">
            <div class="row">
                <div class="col-md-12">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="zf-list-panel col-md-6">
                                <div class="panel-top">
                                    <h5 class="panel-title">
                                        Server Events
                                        <md-button class="md-raised md-primary zf-icon-button my-0"
                                                   @click.stop="showAddEditServerEvent()">
                                            <md-tooltip>Add New Server Event</md-tooltip>
                                            <md-icon>add</md-icon>
                                        </md-button>
                                    </h5>
                                </div>
                                <div class="panel-body">
                                    <md-list class="md-double-line zf-list servers-list">
                                        <md-list-item v-for="event in selectedServer.events" :key="event.id">
                                            <md-switch v-model="event.is_active"
                                                       v-on:change="toggleEventActive(event)"
                                                       aria-label="Server Event Active on/off toggle"></md-switch>
                                            <div class="md-list-item-text">
                                                <span>
                                                    {{event.name}}
                                                </span>
                                                <span>Event Type: {{event.event_type}}</span>
                                            </div>
                                            <md-button class="md-icon-button"
                                                       @click.stop="showAddEditServerEvent(event)">
                                                <md-tooltip>Edit Server Event</md-tooltip>
                                                <md-icon>mode_edit</md-icon>
                                            </md-button>
                                            <md-button class="md-icon-button"
                                                       @click.stop="removeServerEvent(event)">
                                                <md-tooltip>Delete Server Event</md-tooltip>
                                                <md-icon>delete</md-icon>
                                            </md-button>
                                        </md-list-item>
                                    </md-list>
                                </div>
                            </div>
                            <div class="zf-list-panel col-md-6">
                                <div class="panel-top">
                                    <h5 class="panel-title">
                                        Active Users
                                    </h5>
                                </div>
                                <div class="panel-body">
                                    <players-list current-only="true"></players-list>
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
                v-on:server-change="onServerChanged"
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
    import ServerSelect from './ServerSelect';

    export default {
        components: {
            ServerEventAddEdit,
            ServerAddEdit,
            ServerSelect
        },
        data: function() {
            return {
                errors: [],
                rustItems: [],
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
            let itemDate = localStorage.getItem('rustItemsTS');
            let itemsUrl = HTTP.buildUrl('items');

            this.$bus.$on('server-changed', _.bind(this.onSelectedServerChanged, this));
            this.$bus.$emit('get-selected-server');

            HTTP.post(itemsUrl, {
                    items_hash: itemDate
                })
                .then(response => {
                    console.log('ITEMS data: ', response.data);
                    if (!response.data.current) {
                        this.rustItems = response.data.items;
                        localStorage.setItem('rustItems', JSON.stringify(this.rustItems));
                        localStorage.setItem('rustItemsTS', response.data.rustItemsTS);

                    } else {
                        this.rustItems = JSON.parse(localStorage.getItem('rustItems'));
                    }

                    console.log('rustItems: ', this.rustItems);

                })
                .catch(e => {
                    HTTP.logError(itemsUrl, e);

                    this.errors.push(e)
                });
        },
        methods: {
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
                        is_public: false,
                        is_active: false,
                        is_indefinite: true,
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
            onServerChanged: function(changedServer) {
                debugger;
            },

            onSelectedServerChanged: function(changedServer) {
                if (!_.isEmpty(changedServer)) {
                    // add any data parsing we need
                    changedServer.events.forEach(_.bind(function(event) {
                        event.is_active = !!event.is_active;
                        event.is_indefinite = !!event.is_indefinite;
                        event.is_public = !!event.is_public;
                        event.commands = _.isString(event.commands) ? JSON.parse(event.commands) : event.commands;
                        Utils.updateEventTimer(event);
                    }, this));

                    this.selectedServer = changedServer;
                    this.selectedServerId = this.selectedServer.id;
                    sessionStorage.setItem('selected_server_id', this.selectedServerId);
                }
            },

            /**
             * Toggles a server event active or not
             *
             * @param {Object} event The ServerEvent object to toggle
             */
            toggleEventActive: function(event) {
                let active = event.is_active ? 1 : 0;
                let url = HTTP.buildUrl('serverEvents/' + event.id + '/active/' + active);
                HTTP.put(url)
                    .then(response => {
                        console.log('response.data ', response.data);
                    })
                    .catch(e => {
                        HTTP.logError(url, e);

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
                let url = HTTP.buildUrl('serverEvents/' + this.selectedDeleteEvent.id);
                HTTP.delete(url)
                    .then(response => {
                        console.log('response.data ', response.data);
                        this.selectedServer.events = _.reject(this.selectedServer.events, function(evt) {
                            return +evt.id === +response.data.id;
                        });
                    })
                    .catch(e => {
                        HTTP.logError(url, e);

                        this.errors.push(e)
                    });
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
             * @param {object} addedEvent The added event
             */
            onServerEventAdded: function(addedEvent) {
                this.selectedServer.events.push(addedEvent);
            }
        },
        computed: {
            eventClass: function(event){
                return 'server-event my-2 ' + (event.is_active ? '' : 'not-') + 'active';
            }
        }
    }
</script>
