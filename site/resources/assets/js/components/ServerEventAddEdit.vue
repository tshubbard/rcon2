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
                                       v-model="selectedEventType">
                                <md-option v-for="opt in eventTypes"
                                           :key="opt.id"
                                           :value="opt.id">
                                    {{opt.name}}
                                </md-option>
                            </md-select>
                        </md-field>
                    </div>
                    <div class="form-group col-md-6">
                        {{selectedEventTypeText}}
                    </div>
                </div>

                <md-divider></md-divider>

            </md-dialog-content>
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
                selectedEventType: '',
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
                selectedEventTypeText: ''
            }
        },
        created: function() {
            console.log('this: ', this);

            this.eventTypes.forEach(_.bind(function(evt) {
                this.eventTypeObj[evt.id] = evt;
            }, this));
        },
        methods: {
            onChange: function() {
                console.log('onChange: ', arguments);
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
            }
        },
        watch: {
            selectedEventType: function(eventType) {
                let evt = this.eventTypeObj[eventType];

                if (eventType && evt) {
                    this.selectedEventTypeText = evt.help;
                }
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
                    this.selectedEventType = this.data.event_type;

                    return this.data;
                },
                set(data) {
                    let evt = this.eventTypeObj[this.selectedEventType];

                    this.selectedEventType = data.event_type;

                    if (evt) {
                        this.selectedEventTypeText = this.eventTypeObj[this.selectedEventType].help;
                    }

                    this.data = data;
                }
            }
        }
    }
</script>
