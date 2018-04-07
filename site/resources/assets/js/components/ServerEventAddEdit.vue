<template>
    <md-dialog
            :md-active.sync="showDialog"
            aria-label="Add/Edit Rust Server Event Dialog"
            @md-opened="onDialogOpened"
            @md-closed="onDialogClosed"
            class="add-edit-server-event col-11">

        <form name="addEditServerActionEventForm">
            <md-toolbar>
                <div class="md-toolbar-tools">
                    <h2>
                        {{eventData.actionType}} Server Event
                        <md-button class="md-icon-button float-right"
                                   @click.stop="showDialog = false">
                            <i class="material-icons clickable" aria-label="Close dialog">clear</i>
                        </md-button>
                    </h2>
                </div>
            </md-toolbar>
            <md-dialog-content>
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

                <md-divider class="mb-4"></md-divider>

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
        </form>
    </md-dialog>
</template>

<script>
    export default {
        props: [
            'visible',
            'data'
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
                }],
                eventTypeObj: {},
                selectedEventTypeText: '',
                optionsDays: [],
                optionsHours: [],
                optionsMinutes: []
            }
        },
        created: function() {
            console.log('this: ', this);

            this.eventTypes.forEach(_.bind(function(evt) {
                this.eventTypeObj[evt.id] = evt;
            }, this));

            var i;
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
            onSelectedEventTypeChanged: function() {
                //eventData.event_type = selectedEventType
                debugger;
            },

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
            }
        },
        watch: {
            selectedEventType: function(eventType) {
                let evt = this.eventTypeObj[eventType];

                if (eventType && evt) {
                    this.selectedEventTypeText = evt.help;
                }
            },
            eventData: function(a,b,c) {
                console.log('eventData watcher ', arguments);
            }
        },
        computed: {
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
