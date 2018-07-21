<template>
    <div class="account m-3 record" v-cloak>
        <h3 class="title">
            <span class="name">{{ account.name }}</span>
        </h3>

        <errors-view :errors="errors"></errors-view>

        <div class="record-body">
            <div class="account-details md-layout md-gutter">
                <div class="md-layout-item">
                    <h5>Description</h5>
                    <div>
                        <div v-if="!isEdit"
                              class="description"
                              name="description"
                              @click.stop="isEdit = true;">
                            {{ account.description }}
                        </div>

                        <div v-if="isEdit">
                            <md-field>
                                <md-textarea class="description"
                                             v-model="account.description"
                                             name="description"
                                             md-counter="255"
                                             @keyup="onDescriptionChanged"
                                ></md-textarea>
                            </md-field>
                            <div class="zf-suffix-icon save" v-if="hasDescriptionChanged"
                                @click="saveDescription">
                                <md-icon class="md-size-2x">save</md-icon>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="md-layout-item">

                </div>
            </div>

            <div class="md-layout md-gutter">
                <div class="md-layout-item zf-list-panel">
                    <h5 class="panel-title">
                        Servers
                        <md-button class="md-raised md-primary zf-icon-button zf-top-minus-5 my-0"
                                   @click.stop="addEditServer()">
                            <md-tooltip>Add Server</md-tooltip>
                            <md-icon>add</md-icon>
                        </md-button>
                    </h5>
                    <div class="panel-body">
                        <md-list class="md-double-line zf-list servers-list">
                            <md-list-item v-for="server in servers" :key="server.id">
                                <md-icon class="md-primary">computer</md-icon>
                                <div class="md-list-item-text">
                                    <span>
                                        <router-link class="nav-link" :to="'/dashboard/' + server.slug">
                                            {{server.name}}
                                        </router-link>
                                    </span>
                                    <span>
                                        Max Players: {{server.max_players}}
                                    </span>
                                </div>
                                <md-button class="md-icon-button md-list-action"
                                           @click.stop="addEditServer(server)">
                                    <md-tooltip>Edit Server</md-tooltip>
                                    <md-icon>mode_edit</md-icon>
                                </md-button>
                                <md-button class="md-icon-button md-list-action"
                                           @click.stop="deleteServer(server)">
                                    <md-tooltip>Delete Server from Account</md-tooltip>
                                    <md-icon>delete</md-icon>
                                </md-button>
                            </md-list-item>
                        </md-list>
                    </div>
                </div>
                <div class="md-layout-item zf-list-panel">
                    <h5 class="panel-title">
                        Users
                        <md-button class="md-raised md-primary zf-icon-button zf-top-minus-5 my-0"
                                   @click.stop="addEditUser()">
                            <md-tooltip>Add User to Account</md-tooltip>
                            <md-icon>add</md-icon>
                        </md-button>
                    </h5>
                    <div class="panel-body">
                        <md-list class="md-double-line zf-list users-list">
                            <md-list-item v-for="user in users" :key="user.id">
                                <md-icon class="md-primary">person</md-icon>
                                <div class="md-list-item-text">
                                    <span>
                                        <router-link class="nav-link" :to="'/u/' + user.slug">
                                            {{user.name}}
                                        </router-link>
                                    </span>
                                    <span>
                                        Account Role: {{user.role_id}}
                                    </span>
                                </div>
                                <md-button class="md-icon-button md-list-action"
                                           @click.stop="addEditUser(user)">
                                    <md-tooltip>Edit User on Account</md-tooltip>
                                    <md-icon>mode_edit</md-icon>
                                </md-button>
                                <md-button class="md-icon-button md-list-action"
                                           @click.stop="removeUser(user)">
                                    <md-tooltip>Delete User from Account</md-tooltip>
                                    <md-icon>delete</md-icon>
                                </md-button>
                            </md-list-item>
                        </md-list>
                    </div>
                </div>
            </div>
        </div>

        <account-user-add-edit
                :visible="showUserAddEdit"
                :userData="selectedUserForAddEdit"
                v-on:account-user-added="onUserAdded"
                v-on:account-user-deleted="onUserDeleted"
                v-on:account-user-changed="onUserChanged"
                @close="showUserAddEdit = false">
        </account-user-add-edit>
        <server-add-edit
                :visible="showServerAddEdit"
                :serverData="selectedServerForAddEdit"
                v-on:server-added="onServerAdded"
                v-on:server-deleted="onServerDeleted"
                v-on:server-changed="onServerChanged"
                @close="showServerAddEdit = false">
        </server-add-edit>

        <md-dialog-confirm
                :md-active.sync="showDeleteConfirmDialog"
                :md-title="dialogTitle"
                v-model="item"
                :md-content="dialogContent"
                :md-confirm-text="dialogConfirmText"
                md-cancel-text="Cancel"
                @md-cancel="onConfirmDialogCancel"
                @md-confirm="onConfirmDialogConfirm"
        />
    </div>
</template>
<script>
    import {HTTP} from '../app';
    import ErrorsView from './ErrorsView';
    import AccountUserAddEdit from './AccountUserAddEdit';
    import ServerAddEdit from './ServerAddEdit';

    export default {
        components: {
            ErrorsView,
            AccountUserAddEdit,
            ServerAddEdit
        },
        data: function() {
            return {
                account: {},
                dialogAction: '',
                dialogTitle: '',
                dialogContent: '',
                dialogConfirmText: '',
                errors: [],
                hasDescriptionChanged: false,
                isEdit: false,
                item: {},
                servers: [],
                selectedServerForAddEdit: {
                    events: []
                },
                selectedUserForAddEdit: {},
                showUserAddEdit: false,
                showDeleteConfirmDialog: false,
                showServerAddEdit: false,
                synceAccountData: {},
                users: []
            }
        },
        created: function() {
            let url = HTTP.buildUrl('a/' + this.$route.params.accountName);
            HTTP.get(url)
                .then(response => {
                    console.log('account data: ', response);

                    this.account = response.data;
                    this.synceAccountData = _.clone(this.account);
                    this.servers = response.data.servers;
                    this.users = response.data.users;
                })
                .catch(e => {
                    console.error('[API Error] ', url, e);

                    this.errors.push(e)
                });
        },
        methods: {

            addEditServer: function(server) {
                if (server) {
                    this.selectedServerForAddEdit = server;
                    this.item = server;
                } else {
                    this.selectedServerForAddEdit = {
                        account_id: '',
                        disabled: 0,
                        host: '',
                        name: '',
                        password: '',
                        port: '',
                        max_players: ''
                    };
                }

                this.showServerAddEdit = true;
            },

            onDescriptionChanged: function() {
                this.hasDescriptionChanged = this.account.description !== this.synceAccountData.description;
            },

            saveDescription: function() {
                let url = HTTP.buildUrl('accounts/' + this.account.id);
                let payload = _.pick(this.account, 'name', 'description');

                this.hasDescriptionChanged = false;
                this.isEdit = false;

                HTTP.put(url, payload)
                    .then(response => {
                        this.showDeleteConfirmDialog = false;
                        this.account = response.data.record;
                        this.synceAccountData = _.clone(this.account);
                    })
                    .catch(e => {
                        console.error('[API Error] ', url, e);
                        this.errors.push(e);
                    });
            },

            deleteServer: function(server) {
                let name = server.name;
                this.showDeleteConfirmDialog = true;
                this.dialogAction = 'removeServer';
                this.dialogTitle = 'Delete Server: ' + name;
                this.dialogContent = 'Are you sure you wish to delete ' + name + '?';
                this.dialogConfirmText = 'Delete Server';
                this.item = server;
            },

            onServerAdded: function(data) {
                console.log('onServerAdded ', data);
            },

            onServerDeleted: function(data) {
                console.log('onServerDeleted ', data);
            },

            onServerChanged: function(data) {
                console.log('onServerChanged ', data);
            },

            addEditUser: function(user) {
                if (user) {
                    this.selectedUserForAddEdit = user;
                    this.item = user;
                } else {
                    this.selectedUserForAddEdit = {
                        name: '',
                    };
                }

                this.showUserAddEdit = true;
            },

            removeUser: function(user) {
                let name = user.name;
                this.showDeleteConfirmDialog = true;
                this.dialogAction = 'removeUser';
                this.dialogTitle = 'Remove User: ' + name;
                this.dialogContent = 'Are you sure you wish to remove ' + name + ' from this Account?';
                this.dialogConfirmText = 'Remove User';
                this.item = user;
            },

            onUserAdded: function(data) {
                console.log('onUserAdded ', data);
            },

            onUserDeleted: function(data) {
                console.log('onUserDeleted ', data);
            },

            onUserChanged: function(data) {
                console.log('onUserChanged ', data);
            },

            onConfirmDialogCancel: function() {
                this.showDeleteConfirmDialog = false;
            },

            onConfirmDialogConfirm: function() {
                let url = HTTP.buildUrl('accounts/' + this.account.id + '/' + this.dialogAction + '/' + this.item.id);

                HTTP.post(url)
                    .then(response => {
                        this.showDeleteConfirmDialog = false;
                        if (this.dialogAction === 'removeUser') {
                            this.users = _.reject(this.users, function(user) {
                                return user.id === response.data.record.id;
                            }, this);
                        } else if (this.dialogAction === 'removeServer') {
                            this.servers = _.reject(this.servers, function(server) {
                                return server.id === response.data.record.id;
                            }, this);
                        }
                    })
                    .catch(e => {
                        console.error('[API Error] ', url, e);
                        this.errors.push(e);
                    });
            },

        },
        computed: {
        }
    }
</script>
