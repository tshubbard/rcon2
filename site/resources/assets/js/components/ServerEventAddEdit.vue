<template>
    <md-dialog
            :md-active.sync="showDialog"
            aria-label="Add/Edit Rust Server Event Dialog"
            @md-opened="onDialogOpened"
            @md-closed="onDialogClosed"
            class="add-edit-server-event col-11">

        <md-toolbar>
            <div class="md-toolbar-tools">
                <h3>
                    {{eventData.actionType}} Server Event
                    <md-button class="md-icon-button close-button"
                               @click.stop="showDialog = false">
                        <i class="material-icons clickable" aria-label="Close dialog">clear</i>
                    </md-button>
                </h3>
            </div>
        </md-toolbar>
        <md-dialog-content>
            <form name="addEditServerActionEventForm">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <h5>Event Setup</h5>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="event_name">Event Name</label>
                        <input id="event_name" type="text" class="form-control" name="event_name"
                               v-model="eventData.name" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="is_public">Public</label>
                        <div>
                            <md-switch id="is_public" name="is_public" class="is-public"
                                       v-model="eventData.is_public"
                                       aria-label="Server Event Public on/off toggle"></md-switch>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="is_active">Active</label>
                        <div>
                            <md-switch id="is_active" name="is_active" class="is-active"
                                       v-model="eventData.is_active"
                                       aria-label="Server Event Active on/off toggle"></md-switch>
                        </div>
                    </div>
                    <div class="form-group col-md-2" v-show="eventData.actionType === 'Edit'">
                        <label>Votes</label>
                        <div>
                            {{eventData.votes}}
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <md-field>
                            <label for="event_type">Event Type</label>
                            <md-select id="event_type" name="event_type"
                                       v-model="eventData.event_type">
                                <md-option v-for="opt in eventTypes"
                                           :key="opt.id"
                                           :value="opt.id">
                                    {{opt.name}}
                                </md-option>
                            </md-select>
                        </md-field>
                    </div>
                    <div class="form-group col-md-6 event-type-help-text">
                        <div>
                            {{selectedEventTypeText}}
                        </div>
                    </div>
                </div>

                <md-divider class="mb-2"
                            v-show="eventData.event_type === 'player.chat' || eventData.event_type === 'timer'"></md-divider>

                <div class="form-row"
                     v-show="eventData.event_type === 'player.chat' || eventData.event_type === 'timer'">
                    <div class="form-group col-md-6">
                        <h5>Event Type Configuration</h5>
                    </div>
                </div>

                <!-- Chat Trigger -->
                <div class="form-row" v-show="eventData.event_type === 'player.chat'">
                    <div class="form-group col-md-6">
                        <label for="trigger">Chat Trigger Word</label>
                        <div>
                            <input id="trigger" type="text" class="form-control" name="trigger"
                                   v-model="eventData.command_trigger"
                                   :required="eventData.event_type === 'player.chat'">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <small class="form-text text-muted">This is the trigger word that users should type in chat to
                            cause an event to happen. <br/>Ex. !kits, !rules, etc
                        </small>
                    </div>
                </div>

                <!-- Timer Inputs -->
                <div class="form-row" v-show="eventData.event_type === 'timer'">
                    <div class="form-group col-md-2">
                        <label for="interval-days">Days</label>
                        <md-field class="no-padding-top">
                            <md-select id="interval-days" name="interval-days" class="white-select-dropdown"
                                       v-model="eventData.command_interval_days"
                                       aria-label="Interval Days Select">
                                <md-option :value="opt.val"
                                           :key="opt.val"
                                           v-for="opt in optionsDays">
                                    {{opt.val}}
                                </md-option>
                            </md-select>
                        </md-field>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="interval-hours">Hours</label>
                        <md-field class="no-padding-top">
                            <md-select id="interval-hours" name="interval-hours" class="white-select-dropdown"
                                       v-model="eventData.command_interval_hours"
                                       aria-label="Interval Hours Select">
                                <md-option :value="opt.val"
                                           :key="opt.val"
                                           v-for="opt in optionsHours">
                                    {{opt.val}}
                                </md-option>
                            </md-select>
                        </md-field>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="interval-minutes">Minutes</label>
                        <md-field class="no-padding-top">
                            <md-select id="interval-minutes" name="interval-minutes" class="white-select-dropdown"
                                       v-model="eventData.command_interval_minutes"
                                       aria-label="Interval Minutes Select">
                                <md-option :value="opt.val"
                                           :key="opt.val"
                                           v-for="opt in optionsMinutes">
                                    {{opt.val}}
                                </md-option>
                            </md-select>
                        </md-field>
                    </div>
                    <div class="form-group col-md-6">
                        <small class="form-text text-muted">This is the timer or schedule for how often your "Command"
                            should be executed.
                        </small>
                    </div>
                </div>

                <md-divider class="my-3"></md-divider>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <h5>Add Commands</h5>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Templates</label>
                        <md-list class="bordered-list">

                            <md-list-item @click.stop="onAddKeyToCommand('say')">
                                <md-icon>chat</md-icon>
                                <span class="md-list-item-text">say</span>
                            </md-list-item>

                            <md-list-item @click.stop="onAddKeyToCommand('giveto')">
                                <md-icon>person_add</md-icon>
                                <span class="md-list-item-text">giveto</span>
                            </md-list-item>

                            <md-list-item @click.stop="onAddKeyToCommand('giveall')">
                                <md-icon>group_add</md-icon>
                                <span class="md-list-item-text">giveall</span>
                            </md-list-item>

                            <md-list-item @click.stop="onAddKeyToCommand('kick')">
                                <md-icon>exposure_neg_1</md-icon>
                                <span class="md-list-item-text">kick</span>
                            </md-list-item>

                            <md-list-item @click.stop="onAddKeyToCommand('custom')">
                                <md-icon>brush</md-icon>
                                <span class="md-list-item-text">custom</span>
                            </md-list-item>

                        </md-list>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Command</label>
                        <md-field>
                            <md-textarea v-model="selectedServerEvent.command"></md-textarea>
                        </md-field>
                    </div>

                    <div class="form-group col-md-3">
                        <md-tabs>
                            <md-tab id="tab-fields" md-label="Command Fields">
                                <md-list>
                                    <md-list-item v-for="item in serverCommandTargetList"
                                                  :key="item.id"
                                                  @click.stop="onAddTargetToCommand(item.id)">
                                        {{item.id}}
                                    </md-list-item>
                                </md-list>
                            </md-tab>
                            <md-tab id="tab-items" md-label="Items">
                                <md-list-item v-for="item in items"
                                              :key="item.id"
                                              class="command-items-list"
                                              @click.stop="onAddItemToCommand(item)">
                                    {{item.name}}
                                </md-list-item>
                            </md-tab>
                        </md-tabs>
                    </div>
                </div>

                <md-divider class="my-3"></md-divider>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <h5>Event Commands List</h5>
                    </div>
                </div>
                <!--
                <div class="form-row">
                   <div class="form-group col-md-6">

                   </div>
                   <div class="form-group col-md-6">

                   </div>
                </div>
                -->
                <md-table v-model="eventData.commands" md-card>
                    <md-table-row slot="md-table-row" slot-scope="{ item }">
                        <md-table-cell md-label="Order" md-sort-by="order" class="text-center" md-numeric>
                            {{ item.order }}
                        </md-table-cell>
                        <md-table-cell md-label="Type/Key">
                            {{ item.key }}
                        </md-table-cell>
                        <md-table-cell md-label="Command">
                            {{ item.command }}
                        </md-table-cell>
                        <md-table-cell md-label="Actions">

                            <md-button class="slim-button" @click.stop="onEditServerEvent(item)">
                                <md-icon>mode_edit</md-icon>
                            </md-button>

                            <md-button class="slim-button" @click.stop="onDeleteServerEvent(item)">
                                <md-icon>delete</md-icon>
                            </md-button>

                        </md-table-cell>
                    </md-table-row>
                </md-table>

            </form>

        </md-dialog-content>

        <md-divider class="mt-4"></md-divider>

        <md-dialog-actions layout="row" layout-align="end">
            <md-button @click.stop="showDialog = false" type="button">
                Cancel
            </md-button>
            <md-button @click.stop="saveAddEditServerEventDialog()" class="md-primary">
                Save
            </md-button>
        </md-dialog-actions>

    </md-dialog>
</template>

<script>
    export default {
        props: [
            'visible',
            'data',
            'items'
        ],
        data: function() {
            return {
                eventTypes: [{
                    id: 'timer',
                    name: 'Scheduled/Timed',
                    help: 'Post server message every 15 minutes'
                }, {
                    id: 'player.chat',
                    name: 'Chat-Triggered',
                    help: 'Chat-typed messages: !kits, !rules, etc'
                }, {
                    id: 'player.spawned',
                    name: 'Player Spawned',
                    help: 'When a player spawns - occurs on Connect and after death'
                }, {
                    id: 'player.killed',
                    name: 'Player Killed',
                    help: 'When a player is killed'
                }, {
                    id: 'player.connected',
                    name: 'Player Connected',
                    help: 'When the player connects to the server'
                }, {
                    id: 'player.disconnected',
                    name: 'Player Disconnected',
                    help: 'When a player disconnects from the server'
                }, {
                    id: 'cargo_plane',
                    name: 'Cargo Plane / Airdrop',
                    help: 'When the Cargo Plane spawns'
                }, {
                    id: 'patrolhelicopter',
                    name: 'Patrol Helicopter',
                    help: 'When the Patrol Helicopter spawns'
                }, {
                    id: 'ch47scientists.entity',
                    name: 'Chinook Helicopter',
                    help: 'When the Chinook Helicopter with scientists spawns'
                }],
                eventTypeObj: {},
                selectedEventTypeText: '',
                selectedServerEvent: {
                    command: ''
                },
                optionsDays: [],
                optionsHours: [],
                optionsMinutes: [],
                serverCommandTargetList: [],
                serverCommandTargets: {
                    timer: [],
                    'player.chat': [{
                        id: 'user.username',
                        name: 'UserName',
                        help: 'The name of the user sending the message'
                    }, {
                        id: 'user.steam_id',
                        name: 'Steam ID',
                        help: 'The Steam ID of the user sending the message'
                    }],
                    'player.killed': [{
                        id: 'victim.username',
                        name: 'Victim UserName',
                        help: 'The name of the victim'
                    }, {
                        id: 'victim.short_id',
                        name: 'Victim Short ID',
                        help: 'The combatlog ID of the victim'
                    }, {
                        id: 'victim.steam_id',
                        name: 'Victim Steam ID',
                        help: 'The Steam ID of the victim'
                    }, {
                        id: 'killer.username',
                        name: 'Killer UserName',
                        help: 'The name of the killer'
                    }, {
                        id: 'killer.short_id',
                        name: 'Killer Short ID',
                        help: 'The combatlog ID of the killer'
                    }, {
                        id: 'killer.steam_id',
                        name: 'Killer Steam ID',
                        help: 'The Steam ID of the killer'
                    }],
                    'player.spawned': [{
                        id: 'user.username',
                        name: 'UserName',
                        help: 'The name of the user who spawned'
                    }, {
                        id: 'user.short_id',
                        name: 'Short ID',
                        help: 'The Short ID of the user who spawned'
                    }, {
                        id: 'user.steam_id',
                        name: 'Steam ID',
                        help: 'The Steam ID of the user who spawned'
                    }],
                    'player.connected': [{
                        id: 'user.host',
                        name: 'Host',
                        help: 'The Host info of the user who connected'
                    }, {
                        id: 'user.username',
                        name: 'UserName',
                        help: 'The name of the user who connected'
                    }, {
                        id: 'user.steam_id',
                        name: 'Steam ID',
                        help: 'The Steam ID of the user who connected'
                    }],
                    'player.disconnected': [{
                        id: 'user.host',
                        name: 'Host',
                        help: 'The Host info of the user who disconnected'
                    }, {
                        id: 'user.username',
                        name: 'UserName',
                        help: 'The name of the user who disconnected'
                    }, {
                        id: 'user.steam_id',
                        name: 'Steam ID',
                        help: 'The Steam ID of the user who disconnected'
                    }]
                }
            }
        },
        created: function() {
            let i;
            console.log('this: ', this);

            this.eventTypes.forEach(_.bind(function(evt) {
                this.eventTypeObj[evt.id] = evt;
            }, this));

            for (i = 0; i < 31; i++) {
                this.optionsDays.push({
                    val: i
                });
            }
            for (i = 0; i < 24; i++) {
                this.optionsHours.push({
                    val: i
                });
            }
            for (i = 0; i < 60; i++) {
                this.optionsMinutes.push({
                    val: i
                });
            }
        },
        methods: {
            /**
             * When the dialog opens
             */
            onDialogOpened: function() {
                this.selectedEventType = this.data.event_type;
            },

            /**
             * When the dialog closes
             */
            onDialogClosed: function() {
                this.selectedEventType = {};
            },

            /**
             * Validates the server event info and saves or provides user feedback on errors
             */
            saveAddEditServerEventDialog: function() {

                if (this.eventData.event_type === 'timer') {
                    this.eventData.command_timer =
                        (this.eventData.command_interval_days * 1440) +
                        (this.eventData.command_interval_hours * 60) +
                        this.eventData.command_interval_minutes;
                }

                // todo: ADD SAVING

                console.log('saveAddEditServerEventDialog ', this.eventData);
            },

            /**
             * Handles when user clicks to edit a specific server event
             */
            onEditServerEvent: function(serverEvent) {
                console.log('onEditServerEvent: ', serverEvent);
                this.selectedServerEvent = serverEvent;
                this.serverCommandTargetList = this.serverCommandTargets[serverEvent.key];
            },

            /**
             * Handles deleting an event from a server event list
             */
            onDeleteServerEvent: function(serverEvent) {
                console.log('onDeleteServerEvent: ', serverEvent);
                // todo: make this work
            },

            /**
             * Prepends a specific command keyword to the command stromg
             *
             * @param {string} type The type of command keyword to prepend
             */
            onAddKeyToCommand: function(type) {
                this.selectedServerEvent.command = type + ' ' + this.selectedServerEvent.command;
            },

            /**
             * Adds a command target to the command string
             *
             * @param {string} targetId The target id for a command
             */
            onAddTargetToCommand: function(targetId) {
                this.selectedServerEvent.command += ' ${' + targetId + '}';
            },

            onAddItemToCommand: function(itemObj) {
                console.log('onAddItemToCommand: ', itemObj);
            }
        },
        watch: {
            selectedEventType: function(eventType) {
                let evt = this.eventTypeObj[eventType];

                if (eventType && evt) {
                    this.selectedEventTypeText = evt.help;
                    this.serverCommandTargetList = this.serverCommandTargets[eventType];
                }
                console.log('selectedEventType watcher ', this.serverCommandTargetList);

            },
            eventData: function(a,b,c) {
                console.log('eventData watcher ', arguments);
            }
        },
        computed: {
            selectedEventType: {
                get() {
                    return this.eventData.event_type;
                },
                set(value) {
                    this.eventData.event_type = value;
                }
            },
            showDialog: {
                get() {
                    return this.visible;
                },
                set(value) {
                    if (!value) {
                        this.$emit('close');
                    }
                }
            },
            eventData: {
                get() {
                    return this.data;
                },
                set(data) {
                    let evt = this.eventTypeObj[data.event_type];
                    if (evt) {
                        this.selectedEventTypeText = this.eventTypeObj[data.event_type].help;
                    }
                    this.data = data;
                }
            }
        }
    }
</script>
