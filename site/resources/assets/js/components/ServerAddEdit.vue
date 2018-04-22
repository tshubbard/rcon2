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
                    <md-button class="md-icon-button close-button" v-on:click="showDialog = false">
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
                        <input id="server_name" type="text" class="form-control" name="server_name" v-model="item.name" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="disabled">Active</label>
                        <div>
                            <md-switch id="disabled" name="disabled" class="disabled"
                                       v-model="item.active"
                                       aria-label="Server Active on/off toggle"></md-switch>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="server_host">Server Host</label>
                        <input id="server_host" type="text" class="form-control" name="server_host" v-model="item.host" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="server_port">Server Port</label>
                        <input id="server_port" type="text" class="form-control" name="server_port" v-model="item.port" required>
                    </div>
                </div>
            </form>

        </md-dialog-content>

        <md-divider class="mt-4"></md-divider>

        <md-dialog-actions layout="row" layout-align="end">
            <md-button v-on:click="showDialog = false" type="button">
                Cancel
            </md-button>
            <md-button v-on:click="saveAddEditServerDialog()" class="md-primary">
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
                'item': {}
            }
        },
        created: function() {
        },
        methods: {
            /**
             * Validates the server info and saves or provides user feedback on errors
             */
            saveAddEditServerDialog: function() {
                let url = '/api/v1/server';
                let data = _.pick(this.item, 'name', 'host', 'port', 'disabled');
                let method;

                if(this.item.id == null)
                    method = 'POST';
                else if(this.item.delete != null)
                {
                    method = 'DELETE';
                    url += '/' + this.item.id;
                }
                else
                {
                    method = 'PUT';
                    url += '/' + this.item.id;
                }

                $.ajax({'type': method, 'url': url, 'data': data, 'success': function(data){
                    console.log(data);
                }.bind(this), 'dataType': 'json'});

                //this.$emit('server-change', {});
            },

            /**
             * Handles deleting an event from a server event list
             */
            onDeleteServerEvent: function(serverEvent) {
                console.log('onDeleteServerEvent: ', serverEvent);
                // todo: make this work
            },
            onDialogOpened: function(){
                this.item = _.clone(this.serverData);
            },
            onDialogClosed: function(){

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
            }
        }
    }
</script>
