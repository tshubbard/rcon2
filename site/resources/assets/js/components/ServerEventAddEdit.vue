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
                <errors-view :errors="errors"></errors-view>

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
                                       v-model="eventData.event_type"
                                       :disabled="hasEventsInStack">
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
                                       v-model="commandIntervalDays"
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
                                       v-model="commandIntervalHours"
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
                                       v-model="commandIntervalMinutes"
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
                        <h5>Event Schedule</h5>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <md-switch id="is_indefinite" name="is_indefinite" class="is-indefinite"
                                   v-model="eventData.is_indefinite"
                                   :change="onChangeEventIndefinite(eventData)"
                                   aria-label="Server Event Indefinite on/off toggle">
                            {{scheduleMessage}}
                        </md-switch>
                    </div>
                    <div class="form-group col-md-4" v-show="!eventData.is_indefinite">
                        <div>Start Date</div>
                        <md-datepicker v-model="eventData.start_date" md-immediately/>
                    </div>
                    <div class="form-group col-md-4" v-show="!eventData.is_indefinite">
                        <div>End Date</div>
                        <md-datepicker v-model="eventData.end_date" md-immediately/>
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
                            <md-list-item v-for="cmd in serverCommandTemplates"
                                          :key="cmd.name"
                                          @click.stop="onAddKeyToCommand(cmd.name)">
                                <md-icon>{{cmd.icon}}</md-icon>
                                <span class="md-list-item-text">{{cmd.name}}</span>
                            </md-list-item>
                        </md-list>
                    </div>

                    <div class="form-group col-md-5">
                        <label>Command</label>
                        <md-field>
                            <md-textarea ref="command" v-model="selectedServerEvent.command"></md-textarea>
                        </md-field>
                        <div class="text-right">
                            <md-button class="md-raised md-dense md-primary"
                                       @click.stop="addEventToCommandStack()"
                                       :disabled="selectedServerEvent.command.length < 5">
                                <span v-show="selectedServerEvent.isEdit">Update Command</span>
                                <span v-show="!selectedServerEvent.isEdit">Add To Command Stack</span>
                            </md-button>
                        </div>
                    </div>

                    <div class="form-group col-md-4" v-if="serverCommandTargetList.length">
                        <md-tabs class="command-tabs" md-dynamic-height>
                            <md-tab id="tab-fields" md-label="Event Target">
                                <md-list class="command-targets-list">
                                    <md-list-item v-for="item in serverCommandTargetList"
                                                  :key="item.id"
                                                  @click.stop="onAddTargetToCommand(item.id)">
                                        {{item.id}}
                                    </md-list-item>
                                </md-list>
                            </md-tab>
                            <md-tab id="tab-items" md-label="Items">
                                <md-list-item v-for="category in items"
                                              class="command-items-list md-dense md-scrollbar list-style-type-none"
                                              :key="category.id" md-expand>
                                    <span class="md-list-item-text">{{category.name}}</span>

                                    <md-list slot="md-expand" class="md-dense md-content">
                                        <md-list-item v-for="item in category.items"
                                                      class="md-inset list-style-type-none"
                                                      :key="item.id"
                                                      @click.stop="onAddItemToCommand(item)">
                                            {{item.name}}
                                        </md-list-item>
                                    </md-list>
                                </md-list-item>
                            </md-tab>
                        </md-tabs>
                    </div>
                    <div class="form-group col-md-4" v-show="serverCommandTargetList.length === 0">
                        <md-tabs class="command-tabs" md-dynamic-height>
                            <md-tab id="tab-items" md-label="Items">
                                <md-list-item v-for="category in items"
                                              class="command-items-list md-dense md-scrollbar list-style-type-none"
                                              :key="category.id" md-expand>
                                    <span class="md-list-item-text">{{category.name}}</span>

                                    <md-list slot="md-expand" class="md-dense md-content">
                                        <md-list-item v-for="item in category.items"
                                                      class="md-inset list-style-type-none"
                                                      :key="item.id"
                                                      @click.stop="onAddItemToCommand(item)">
                                            {{item.name}}
                                        </md-list-item>
                                    </md-list>
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

                <table class="table server-events-table">
                    <thead>
                        <tr>
                            <th style="width: 8.33%">Order</th>
                            <th style="width: 8.33%">Type/Key</th>
                            <th style="width: 66.66%">Command</th>
                            <th style="width: 16.66%">Actions</th>
                        </tr>
                    </thead>
                    <draggable v-model="eventDataCommands" :element="'tbody'">
                        <tr class="server-event-row" v-for="item in sortByOrder(eventDataCommands)" :key="item.order">
                            <td class="text-center">{{item.order}}</td>
                            <td>{{item.key}}</td>
                            <td>{{item.command}}</td>
                            <td>
                                <md-button class="slim-button" @click.stop="onEditServerEvent(item)">
                                    <md-icon>mode_edit</md-icon>
                                </md-button>
                                <md-button class="slim-button" @click.stop="onDeleteServerEvent(item)">
                                    <md-icon>delete</md-icon>
                                </md-button>
                            </td>
                        </tr>
                    </draggable>
                </table>
            </form>

        </md-dialog-content>

        <md-divider class="mt-4"></md-divider>

        <md-dialog-actions layout="row" layout-align="end">
            <md-button
                    @click.stop="showDialog = false"
                    class="md-raised">
                Cancel
            </md-button>
            <md-button
                    @click.stop="checkForm"
                    class="md-raised md-primary">
                Save
            </md-button>
        </md-dialog-actions>

    </md-dialog>
</template>

<script>
    import {HTTP} from '../app';
    import {Utils} from '../utils';
    import draggable from 'vuedraggable';
    import ErrorsView from './ErrorsView';

    export default {
        components: {
            draggable,
            ErrorsView
        },
        props: [
            'visible',
            'data',
            'items'
        ],
        data: function() {
            return {
                commandIntervalDays: 0,
                commandIntervalHours: 0,
                commandIntervalMinutes: 0,
                errors: [],
                eventTypes: [{
                    id: 'timer',
                    name: 'Scheduled/Timed',
                    help: 'Post server message every 15 minutes',
                    targets: []
                }, {
                    id: 'player.chat',
                    name: 'Chat-Triggered',
                    help: 'Chat-typed messages: !kits, !rules, etc',
                    targets: [{
                        id: 'user.username',
                        name: 'UserName',
                        help: 'The name of the user sending the message'
                    }, {
                        id: 'user.steam_id',
                        name: 'Steam ID',
                        help: 'The Steam ID of the user sending the message'
                    }]
                }, {
                    id: 'player.spawned',
                    name: 'Player Spawned',
                    help: 'When a player spawns - occurs on Connect and after death',
                    targets: [{
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
                    }]
                }, {
                    id: 'player.killed',
                    name: 'Player Killed',
                    help: 'When a player is killed',
                    targets: [{
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
                    }]
                }, {
                    id: 'player.connected',
                    name: 'Player Connected',
                    help: 'When the player connects to the server',
                    targets: [{
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
                    }]
                }, {
                    id: 'player.disconnected',
                    name: 'Player Disconnected',
                    help: 'When a player disconnects from the server',
                    targets: [{
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
                }, {
                    id: 'cargo_plane',
                    name: 'Cargo Plane / Airdrop',
                    help: 'When the Cargo Plane spawns',
                    targets: []
                }, {
                    id: 'patrolhelicopter',
                    name: 'Patrol Helicopter',
                    help: 'When the Patrol Helicopter spawns',
                    targets: []
                }, {
                    id: 'ch47scientists.entity',
                    name: 'Chinook Helicopter',
                    help: 'When the Chinook Helicopter with scientists spawns',
                    targets: []
                }, {
                    id: 'xmasrefill',
                    name: 'Christmas Gift Drop',
                    help: 'When the Christmas gifts are dropped by Santa',
                    targets: []
                }],
                eventTypeObj: {},
                hasEventsInStack: false,
                optionsDays: [],
                optionsHours: [],
                optionsMinutes: [],
                scheduleMessage: 'Event is Always Active',
                selectedEventTypeText: '',
                selectedServerEvent: {
                    command: '',
                    isEdit: false,
                    key: undefined
                },
                serverCommandTemplates: [{
                    name: 'say',
                    icon: 'chat'
                }, {
                    name: 'giveto',
                    icon: 'person_add'
                }, {
                    name: 'giveall',
                    icon: 'group_add'
                }, {
                    name: 'kick',
                    icon: 'exposure_neg_1'
                }, {
                    name: 'custom',
                    icon: 'brush'
                }],
                serverCommandTargetList: []
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
                this.errors = [];
            },

            /**
             * When the dialog closes
             */
            onDialogClosed: function() {
                this.selectedEventType = {};
                this.selectedServerEvent = {
                    command: '',
                    isEdit: false,
                    key: undefined
                };
                this.errors = [];
            },

            /**
             * Validates the server event info and saves or provides user feedback on errors
             */
            saveAddEditServerEventDialog: function() {
                let method;
                let url = HTTP.buildUrl('serverEvents');
                let eventName;
                let payload = _.clone(this.eventData);
                let tmpDate;
                let tmpMonth;

                if (payload.event_type === 'timer') {
                    payload.command_timer =
                        (payload.command_interval_days * 1440) +
                        (payload.command_interval_hours * 60) +
                        payload.command_interval_minutes;
                }

                if (payload.is_public === 'on' || payload.is_public === 'off') {
                    payload.is_public = payload.is_public === 'on' ? 1 : 0;
                }
                if (payload.is_active === 'on' || payload.is_active === 'off') {
                    payload.is_active = payload.is_active === 'on' ? 1 : 0;
                }

                if (!payload.is_active) {
                    payload.is_active = 0;
                }

                if (payload.is_indefinite) {
                    payload.start_date = null;
                    payload.end_date = null;
                } else {
                    payload.is_indefinite = 0;
                    if (payload.start_date) {
                        tmpDate = new Date(payload.start_date);
                        tmpMonth = tmpDate.getMonth();
                        if (tmpMonth < 10) {
                            tmpMonth = '0' + tmpMonth.toString();
                        }
                        payload.start_date = tmpDate.getUTCFullYear() + '-' +
                            tmpMonth + '-' +
                            tmpDate.getDate();
                    }

                    if (payload.start_date) {
                        tmpDate = new Date(payload.end_date);
                        tmpMonth = tmpDate.getMonth();
                        if (tmpMonth < 10) {
                            tmpMonth = '0' + tmpMonth.toString();
                        }
                        payload.end_date = tmpDate.getUTCFullYear() + '-' +
                            tmpMonth + '-' +
                            tmpDate.getDate();
                    }
                }

                if (payload.id) {
                    method = 'put';
                    url += '/' + payload.id;
                    eventName = 'event-changed';
                } else {
                    method = 'post';
                    eventName = 'event-added';
                }

                payload.commands = JSON.stringify(payload.commands);
                console.log('PAYLOAD DATA: ', payload);

                HTTP[method](url, payload)
                    .then(response => {
                        let eventData = _.clone(response.data);

                        eventData.is_active = !!eventData.is_active;
                        eventData.is_indefinite = !!eventData.is_indefinite;
                        eventData.is_public = !!eventData.is_public;
                        eventData.commands = JSON.parse(eventData.commands);
                        Utils.updateEventTimer(event);
                        console.log('response: ', response);
                        console.log('eventData: ', eventData);

                        this.$emit(eventName, eventData);
                        this.showDialog = false;
                    })
                    .catch(e => {
                        HTTP.logError(url, e);

                        this.errors.push(e)
                    });

                console.log('saveAddEditServerEventDialog eventData ', this.eventData);
            },

            /**
             * Handles when user clicks to edit a specific server event
             */
            onEditServerEvent: function(serverEvent) {
                console.log('onEditServerEvent: ', serverEvent);
                this.selectedServerEvent = serverEvent;
                this.selectedServerEvent.isEdit = true;
                this.$refs.command.$el.focus();
            },

            /**
             * Handles deleting an event from a server event list
             */
            onDeleteServerEvent: function(serverEvent) {
                let len = 0;
                // remove the event from the list
                this.eventData.commands.splice(serverEvent.order - 1, 1);
                len = this.eventData.commands.length + 1;
                for (let i = 1; i < len; i++) {
                    this.eventData.commands[i - 1].order = i;
                }

                this.hasEventsInStack = !!this.eventData.commands.length;
            },

            addEventToCommandStack: function() {
                console.log('addEventToCommandStack  ', this.selectedServerEvent);

                if (this.selectedServerEvent.order) {
                    this.eventData.commands[this.selectedServerEvent.order - 1] = this.selectedServerEvent;
                } else {
                    this.selectedServerEvent.order = this.eventData.commands.length + 1;
                    this.eventData.commands.push(this.selectedServerEvent);
                }

                this.selectedServerEvent = {
                    command: '',
                    isEdit: false,
                    key: undefined
                };

                this.hasEventsInStack = !!this.eventData.commands.length;
            },

            /**
             * Prepends a specific command keyword to the command stromg
             *
             * @param {string} type The type of command keyword to prepend
             */
            onAddKeyToCommand: function(type) {
                // custom event types dont add "custom" to the beginning
                var cmd = type === 'custom' ? '' : type;

                if (this.selectedServerEvent.key && this.selectedServerEvent.key !== 'custom' &&
                    (this.selectedServerEvent.command.indexOf(this.selectedServerEvent.key) !== -1)) {
                    // if there's already a selected event key, replace it in the string with the new key
                    cmd = this.selectedServerEvent.command.replace(this.selectedServerEvent.key, cmd);
                } else {
                    // otherwise prepend the new command type to the command string
                    cmd = cmd + ' ' + this.selectedServerEvent.command;
                }

                this.selectedServerEvent.command = cmd;
                this.selectedServerEvent.key = type;
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
                this.selectedServerEvent.command += ' ' + itemObj.console_id;
            },

            onChangeEventIndefinite: function(eventData) {
                if (eventData.is_indefinite) {
                    this.scheduleMessage = 'Event is Always Active';
                } else {
                    this.scheduleMessage = 'Event Active Between Start & End Dates';
                }
            },

            checkForm: function(evt) {
                evt.preventDefault();

                this.errors = [];

                if(!this.eventData.name) {
                    this.errors.push('Name is required');
                }

                if (this.eventData.event_type === 'timer') {
                    let timeCheck = (this.eventData.command_interval_days * 1440) +
                        (this.eventData.command_interval_hours * 60) +
                        this.eventData.command_interval_minutes;

                    if (timeCheck < 5) {
                        this.errors.push('Scheduled Time interval must be greater than 5 minutes');
                    }
                }

                if(!this.eventData.is_indefinite) {
                    if (!this.eventData.start_date) {
                        this.errors.push('Start Date is required');
                    }
                    if (!this.eventData.end_date) {
                        this.errors.push('End Date is required');
                    }

                    if (this.eventData.start_date && this.eventData.end_date) {
                        let startDate = new Date(this.eventData.start_date);
                        let endDate = new Date(this.eventData.end_date);

                        if (startDate.getTime() > endDate.getTime()) {
                            this.errors.push('Start Date must be BEFORE the end date jackass...')
                        }
                    }
                }

                if(!this.errors.length) {
                    this.saveAddEditServerEventDialog();
                }
            },

            /**
             * Updates the schedule/timer help text message
             */
            updateIntervalText: function() {
                let word;
                let trim = false;
                this.selectedEventTypeText = 'Post server message every ';

                if (this.eventData.command_interval_days) {
                    trim = true;
                    word = this.eventData.command_interval_days === 1 ? 'day' : 'days';
                    this.selectedEventTypeText += this.eventData.command_interval_days.toString() +
                        ' ' + word + ', ';
                }

                if (this.eventData.command_interval_hours) {
                    trim = true;
                    word = this.eventData.command_interval_hours === 1 ? 'hour' : 'hours';
                    this.selectedEventTypeText += this.eventData.command_interval_hours.toString() +
                        ' ' + word + ', ';
                }

                if (this.eventData.command_interval_minutes) {
                    trim = true;
                    word = this.eventData.command_interval_minutes === 1 ? 'minute' : 'minutes';
                    this.selectedEventTypeText += this.eventData.command_interval_minutes.toString() +
                        ' ' + word + ', ';
                }

                if (trim) {
                    this.selectedEventTypeText = this.selectedEventTypeText.substring(
                        0,
                        this.selectedEventTypeText.length - 2
                    );
                }
            },

            sortByOrder: function(items) {
                return _.sortBy(items, 'order');
            }
        },
        watch: {
            selectedEventType: function(eventType) {
                let evt = this.eventTypeObj[eventType];

                if (eventType && evt) {
                    this.selectedEventTypeText = evt.help;
                    this.serverCommandTargetList = this.eventTypeObj[eventType].targets;
                }
                console.log('selectedEventType watcher ', this.serverCommandTargetList);

            },
            eventData: function(event) {
                console.log('eventData watcher ', arguments);
                this.hasEventsInStack = !!event.commands.length;
                this.commandIntervalDays = event.command_interval_days;
                this.commandIntervalHours = event.command_interval_hours;
                this.commandIntervalMinutes = event.command_interval_minutes;
            },
            commandIntervalDays: function(value) {
                this.eventData.command_interval_days = value;
                this.updateIntervalText();
            },
            commandIntervalHours: function(value) {
                this.eventData.command_interval_hours = value;
                this.updateIntervalText();
            },
            commandIntervalMinutes: function(value) {
                this.eventData.command_interval_minutes = value;
                this.updateIntervalText();
            },
        },
        computed: {
            eventDataCommands: {
                get() {
                    return this.eventData.commands;
                },
                set(commands) {
                    _.each(commands, function(cmd, index) {
                        cmd.order = index + 1;
                    });
                    this.eventData.commands = commands;
                }
            },
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
                    console.log('eventData set data: ', data);

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
