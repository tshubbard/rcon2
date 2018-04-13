<template>
    <md-dialog
            :md-active.sync="showDialog"
            aria-label="Add/Edit Rust Server Dialog"
            @md-opened="onDialogOpened"
            @md-closed="onDialogClosed"
            class="add-edit-server col-11">

        <md-toolbar>
            <div class="md-toolbar-tools">
                <h3>
                    Add/Edit Server
                    <md-button class="md-icon-button close-button" @click.stop="showDialog = false">
                        <i class="material-icons clickable" aria-label="Close dialog">clear</i>
                    </md-button>
                </h3>
            </div>
        </md-toolbar>
        <md-dialog-content>
            <form name="addEditServerActionForm">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <h5>Server Setup</h5>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="server_name">Server Name</label>
                        <input id="server_name" type="text" class="form-control" name="server_name" v-model="serverData.name" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="disabled">Active</label>
                        <div>
                            <md-switch id="disabled" name="disabled" class="disabled"
                                       v-model="serverData.active"
                                       aria-label="Server Active on/off toggle"></md-switch>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="server_host">Server Host</label>
                        <input id="server_host" type="text" class="form-control" name="server_host" v-model="serverData.host" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="server_port">Server Port</label>
                        <input id="server_port" type="text" class="form-control" name="server_port" v-model="serverData.port" required>
                    </div>
                </div>
            </form>

        </md-dialog-content>

        <md-divider class="mt-4"></md-divider>

        <md-dialog-actions layout="row" layout-align="end">
            <md-button @click.stop="showDialog = false" type="button">
                Cancel
            </md-button>
            <md-button @click.stop="saveAddEditServerDialog()" class="md-primary">
                Save
            </md-button>
        </md-dialog-actions>

    </md-dialog>
</template>

<script>
    export default {
	    props: [
            'serverData',
            'visible'
        ],
        data: function() {
            return {
            }
        },
        created: function() {
        },
        methods: {
            /**
             * Validates the server info and saves or provides user feedback on errors
             */
            saveAddEditServerDialog: function() {
            },

            /**
             * Handles deleting an event from a server event list
             */
            onDeleteServerEvent: function(serverEvent) {
                console.log('onDeleteServerEvent: ', serverEvent);
                // todo: make this work
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
