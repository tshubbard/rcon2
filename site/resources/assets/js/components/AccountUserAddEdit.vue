<template>
    <md-dialog
            :md-active.sync="showDialog"
            aria-label="Add/Edit User on Account Dialog"
            @md-opened="onDialogOpened"
            @md-closed="onDialogClosed"
            class="add-edit-server col-8">

        <md-toolbar>
            <div class="md-toolbar-tools">
                <h3>
                    {{addEditLabel}} User on Account
                    <md-button
                            class="md-icon-button close-button"
                            @click.stop="showDialog = false">
                        <i class="material-icons clickable" aria-label="Close dialog">clear</i>
                    </md-button>
                </h3>
            </div>
        </md-toolbar>
        <md-dialog-content>
            <form name="addEditAccountUserForm">
                <errors-view :errors="errors"></errors-view>

                <div class="form-group row" v-if="!item.id">
                    <label for="user_name" class="col-md-3 col-form-label">Email</label>
                    <input id="user_name" type="email" class="col-sm-6 form-control"
                           @keyup.enter="enterClicked"
                           @keydown.enter="enterClicked"
                           name="user_name" v-model="item.email" required>
                </div>
                <div class="form-group row" v-if="item.id">
                    <label for="user_name" class="col-md-3 col-form-label" v-if="item.id">User Name</label>
                    <input id="user_name" type="text" class="col-sm-6 form-control"
                           @keyup.enter="enterClicked"
                           @keydown.enter="enterClicked"
                           name="user_name" v-model="item.name" required>
                </div>
            </form>
        </md-dialog-content>

        <md-divider class="mt-4"></md-divider>

        <md-dialog-actions layout="row" layout-align="end">
            <div class="col-md-4 pl-0">
                <md-button class="md-raised md-accent"
                           v-if="item.id != null && item.id !== userId && accountData.owner_id === userId"
                           v-on:click="removeUser">
                    Remove User
                </md-button>
            </div>
            <div class="col-md-8 text-right">
                <md-button
                        @click.stop="showDialog = false"
                        class="md-raised">
                    Cancel
                </md-button>
                <md-button
                        @click.stop="verifyUser"
                        class="md-raised md-primary" v-if="!isVerified">
                    Check User
                </md-button>
                <md-button
                        @click.stop="checkForm"
                        class="md-raised md-primary" v-if="isVerified">
                    Save
                </md-button>
            </div>
        </md-dialog-actions>

        <md-dialog-confirm
                :md-active.sync="showConfirmDialog"
                md-title="Remove User from Account?"
                v-model="item"
                md-content="Are you sure you wish to remove this user from this Account?"
                md-confirm-text="Remove User"
                md-cancel-text="Cancel"
                @md-cancel="onRemoveUserCancel"
                @md-confirm="onRemoveUserConfirm"
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
            'accountData',
            'authedUserData',
            'userData',
            'usersData',
            'visible'
        ],
        data: function() {
            return {
                acctData: {},
                addEditLabel: '',
                authUser: {},
                errors: [],
                isSaving: false,
                isVerified: false,
                item: {},
                showConfirmDialog: false,
                userId: -1,
                verifiedUser: {},
            }
        },
        created: function() {
            this.isSaving = false;
        },
        methods: {
            onDialogOpened: function(){
                this.errors = [];
                this.acctData = _.clone(this.accountData);
                this.authUser = _.clone(JSON.parse(sessionStorage.getItem('me')));
                this.item = _.clone(this.userData);

                this.addEditLabel = this.item.id ? 'Edit' : 'Add';
                this.userId = this.authUser.id;
                if (this.item.id) {
                    this.isVerified = true;
                }
                console.log('[AccountUserAddEdit] this.item: ', this.item, this.userId);
            },

            onDialogClosed: function(){
                this.errors = [];
            },

            saveAddEditAccountDialog: function() {
                let url = HTTP.buildUrl('accounts/' + this.accountData.id + '/user/');
                let eventName;
                let payload = _.clone(this.item);
                let method;

                if (this.isSaving) {
                    return;
                }
                this.isSaving = true;
                if (!this.item.id) {
                    method = 'post';
                    eventName = 'account-user-added';
                    url += this.verifiedUser.id;
                } else if (this.item.delete) {
                    method = 'delete';
                    url += this.item.id;
                    eventName = 'account-user-deleted';
                } else {
                    method = 'put';
                    url += this.item.id;
                    eventName = 'account-user-changed';
                }

                HTTP[method](url, payload)
                    .then(response => {
                        this.$emit(eventName, _.clone(response.data.record));
                        this.isSaving = false;
                        this.showDialog = false;
                    })
                    .catch(e => {
                        console.error('[API Error] ', url, e);
                        this.isSaving = false;

                        this.errors.push(e)
                    });
            },

            removeUser: function() {
                this.showConfirmDialog = true;
            },


            onRemoveUserCancel: function() {
                this.showConfirmDialog = false;
            },

            onRemoveUserConfirm: function() {
                this.item.delete = true;
                this.checkForm();
            },

            enterClicked: function(evt) {
                evt.preventDefault();
                evt.stopImmediatePropagation();

                this.checkForm(evt);
            },

            verifyUser: function() {
                let regEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                let url;
                let userAlreadyExists = false;
                let item;

                if (this.isVerifying) {
                    return;
                }

                this.errors = [];
                if (regEx.test(this.item.email)) {
                    item = this.item;
                    _.each(this.usersData, function(user) {
                        if (user.email === item.email) {
                            userAlreadyExists = true;
                        }
                    }, this);

                    if (!userAlreadyExists) {
                        url = HTTP.buildUrl('accounts/' + this.accountData.id + '/user/' + this.item.email + '/check');

                        this.isVerifying = true;

                        HTTP.post(url)
                            .then(response => {

                                this.isVerifying = false;
                                if (response.data.success && !_.isNull(response.data.record)) {
                                    this.isVerified = true;
                                    this.verifiedUser = response.data.record;
                                    this.saveAddEditAccountDialog();
                                } else if (!response.data.success && response.data.message) {
                                    this.errors.push(response.data.message)
                                } else {
                                    this.errors.push('Not a valid User');
                                }
                            })
                            .catch(e => {
                                console.error('[API Error] ', url, e);
                                this.isSaving = false;

                                this.errors.push(e)
                            });
                    } else {
                        this.errors.push('User already exists on account!');
                    }
                } else {
                    this.errors.push('Not a valid Email Address');
                }
            },

            checkForm: function(evt) {
                if (evt) {
                    evt.preventDefault();
                    evt.stopImmediatePropagation();
                }

                if (this.isSaving) {
                    return;
                }

                this.errors = [];

                if(this.isVerified && !this.item.name) {
                    this.errors.push('Name is required');
                }
                if(!this.isVerified && !this.item.email) {
                    this.errors.push('Email is required');
                }

                if(!this.errors.length) {
                    if (this.isVerified) {
                        this.saveAddEditAccountDialog();
                    } else {
                        this.verifyUser();
                    }
                }
            },
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
