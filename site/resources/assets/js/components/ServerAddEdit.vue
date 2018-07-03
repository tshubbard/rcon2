<template>
    <md-dialog
            :md-active.sync="showDialog"
            aria-label="Add/Edit Rust Server Dialog"
            @md-opened="onDialogOpened"
            @md-closed="onDialogClosed"
            class="add-edit-server col-8">

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
                <errors-view :errors="errors"></errors-view>

                <div class="form-group row">
                    <label for="server_name" class="col-md-3 col-form-label">Server Name</label>
                    <input id="server_name" type="text" class="col-sm-6 form-control"
                           name="server_name" v-model="item.name" required>
                </div>
                <div class="form-group row active-switch">
                    <label for="disabled" class="col-md-3 col-form-label">Active</label>
                    <md-switch id="disabled" name="disabled" class="col-sm-6"
                               v-model="item.active"
                               aria-label="Server Active on/off toggle"></md-switch>
                </div>
                <div class="form-group row">
                    <label for="account_id" class="col-sm-3 col-form-label">Account</label>
                    <div class="col-sm-6 account-select">
                        <md-field class="account-select-field">
                            <md-select id="account_id" v-model="item.account_id" required>
                                <md-option v-for="acct in accounts"
                                           :value="acct.id" :key="acct.id">{{acct.name}}
                                </md-option>
                            </md-select>
                        </md-field>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="server_host" class="col-sm-3 col-form-label">Server Host</label>
                    <input id="server_host" class="form-control col-sm-6" type="text"
                           name="server_host" v-model="item.host" required>
                </div>
                <div class="form-group row">
                    <label for="server_port" class="col-sm-3 col-form-label">Server Port</label>
                    <input id="server_port" class="form-control col-sm-6" type="number"
                           name="server_port" v-model="item.port" required>
                </div>
                <div class="form-group row">
                    <label for="server_password" class="col-sm-3 col-form-label">Server Password</label>
                    <input id="server_password" class="form-control col-sm-6" type="text"
                           name="server_password" v-model="item.password">
                </div>
            </form>
        </md-dialog-content>

        <md-divider class="mt-4"></md-divider>

        <md-dialog-actions layout="row" layout-align="end">
            <div class="col-md-4 pl-0">
                <md-button class="md-raised md-accent" v-if="item.id != null" v-on:click="deleteServer">Delete This
                    Server
                </md-button>
            </div>
            <div class="col-md-8 text-right">
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
            </div>
        </md-dialog-actions>

        <md-dialog-confirm
                :md-active.sync="showConfirmDialog"
                md-title="Delete Server?"
                v-model="item"
                md-content="Are you sure you wish to delete this server and all its data?"
                md-confirm-text="Delete Server"
                md-cancel-text="Cancel"
                @md-cancel="onDeleteServerCancel"
                @md-confirm="onDeleteServerConfirm"
        />
    </md-dialog>
</template>

<script>
    import {HTTP} from '../app';
    import ErrorsView from './ErrorsView';

    export default {
        components: {
            ErrorsView
        },
        props: [
            'serverData',
            'visible'
        ],
        data: function() {
            return {
                accounts: {},
                errors: [],
                item: {},
                showConfirmDialog: false
            }
        },
        created: function() {
            this.accounts = JSON.parse(sessionStorage.getItem('accounts'));
        },
        methods: {
            /**
             * Validates the server info and saves or provides user feedback on errors
             */
            saveAddEditServerDialog: function() {
                let url = '/api/v1/server';
                let payload = _.pick(this.item, 'account_id', 'name', 'host', 'port', 'password', 'disabled');
                let method;
                let eventName;

                if (payload.password == null || payload.password == '') {
                    delete payload.password;
                }

                if (this.item.id == null) {
                    method = 'POST';
                    eventName = 'server-added';
                } else if (this.item.delete) {
                    method = 'DELETE';
                    url += '/' + this.item.id;
                    eventName = 'server-deleted';
                } else {
                    method = 'PUT';
                    url += '/' + this.item.id;
                    eventName = 'server-changed';
                }

                HTTP[method](url, payload)
                    .then(response => {
                        this.$emit(eventName, _.clone(response.data));
                        this.isSaving = false;
                        this.showConfirmDialog = false;
                    })
                    .catch(e => {
                        this.errors.push(e);
                    });
            },

            deleteServer: function() {
                this.showConfirmDialog = true;
            },

            onDeleteServerCancel: function() {
                this.showConfirmDialog = false;
            },

            onDeleteServerConfirm: function() {
                this.item.delete = true;
                this.checkForm();
            },

            checkForm: function(evt) {
                evt.preventDefault();

                this.errors = [];

                if (!this.item.name) {
                    this.errors.push('Name is required');
                }
                if (!this.item.host) {
                    this.errors.push('Host is required');
                }
                if (!this.item.port) {
                    this.errors.push('Port is required');
                }
                if (!this.item.password) {
                    this.errors.push('Password is required');
                }
                if (!this.item.account_id) {
                    this.errors.push('Server must be assigned an Account');
                }

                if (!this.errors.length) {
                    this.saveAddEditServerDialog();
                }
            },

            onDialogOpened: function() {
                this.errors = [];
                this.item = _.clone(this.serverData);
            },
            onDialogClosed: function() {
                this.errors = [];
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
