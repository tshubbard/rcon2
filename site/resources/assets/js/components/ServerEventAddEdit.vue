<template>
    <md-dialog
            :md-active.sync="showDialog"
            aria-label="Add/Edit Rust Server Event Dialog"
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
                    <div class="form-group col-md-3">
                        <label for="is_public">Visible to Public</label>
                        <div>
                            <md-switch id="is_public" name="is_public" class="is-public"
                                       v-model="eventData.is_public"
                                       aria-label="Server Event Public on/off toggle"></md-switch>
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
                    <div class="form-group col-md-6">
                        {{selectedEventTypeText}}
                    </div>
                </div>

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
                selectedEventTypeText: ''
            }
        },
        created: function() {
            console.log('this: ', this);
        },
        methods: {
            onChange: function() {
                console.log('onChange: ', arguments);

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
                    this.data = data;
                }
            }
        }
    }
</script>
