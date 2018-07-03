<template>
    <div class="user m-3 record" v-cloak>
        <h3 class="title">
            <span class="name">{{ user.name }}</span>
        </h3>

        <errors-view :errors="errors"></errors-view>

        <div class="container-fluid">
            <div class="row record-body">
                <div class="col-md-6">
                    <h5>User Info</h5>
                    <div>
                        <label for="name">Email: </label>
                        <span class="name" id="name" name="name">{{ user.email }}</span>
                    </div>
                </div>
                <div class="col-md-6">

                    <h5 v-cloak>
                        Accounts Info
                        <md-button class="md-raised md-primary zf-icon-button zf-top-minus-10"
                                   @click.stop="showAddEditAccount()">
                            <md-icon>add</md-icon>
                        </md-button>
                    </h5>

                    <div>
                        <ul class="user-accounts">
                            <li v-for="acct in accounts">
                                <router-link class="nav-link display-inline-block" :to="'/a/' + acct.slug">
                                    {{acct.name}}
                                </router-link>
                                <span class="server-actions float-right">

                                    <md-button class="md-raised md-primary zf-icon-button zf-top-minus-15 my-0"
                                               @click.stop="showAddEditAccount(acct)">
                                        <md-tooltip md-direction="top">Edit Account</md-tooltip>
                                        <md-icon>mode_edit</md-icon>
                                    </md-button>
                                    <md-button v-if="acct.owner_id === user.id"
                                               class="md-raised md-accent zf-icon-button zf-top-minus-15 my-0 mr-0"
                                               @click.stop="removeAccount(acct)">
                                        <md-tooltip md-direction="top">Delete Account</md-tooltip>
                                        <md-icon>delete</md-icon>
                                    </md-button>
                                    <md-button v-if="acct.owner_id !== user.id"
                                               class="md-raised md-accent zf-icon-button zf-top-minus-15 my-0 mr-0"
                                               @click.stop="leaveAccount(acct)">
                                        <md-tooltip md-direction="top">Leave Account</md-tooltip>
                                        <md-icon>exit_to_app</md-icon>
                                    </md-button>
                                </span>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>

        <md-dialog-confirm
                :md-active.sync="showDeleteAccount"
                md-title="Delete Account"
                v-model="selectedDeleteAccount"
                md-content="Are you sure you want to delete the account?"
                md-confirm-text="Delete Account"
                md-cancel-text="Cancel"
                @md-cancel="onDeleteAccountCancel"
                @md-confirm="onDeleteAccountConfirm"
        />
        <md-dialog-confirm
                :md-active.sync="showLeaveAccount"
                md-title="Leave Account"
                v-model="selectedLeaveAccount"
                md-content="Are you sure you want to leave this account?"
                md-confirm-text="Leave Account"
                md-cancel-text="Cancel"
                @md-cancel="onLeaveAccountCancel"
                @md-confirm="onLeaveAccountConfirm"
        />

        <account-add-edit
                :visible="showAccountAddEdit"
                :accountData="selectedAccountForAddEdit"
                v-on:account-changed="onAccountChanged"
                v-on:account-added="onAccountAdded"
                @close="showAccountAddEdit = false">
        </account-add-edit>
    </div>
</template>
<script>
    import {HTTP} from '../app';
    import AccountAddEdit from './AccountAddEdit';
    import ErrorsView from './ErrorsView';

    export default {
        components: {
            AccountAddEdit,
            ErrorsView
        },
        data: function() {
            return {
                accounts: [],
                errors: [],
                user: [],
                selectedAccountForAddEdit: {},
                selectedDeleteAccount: {},
                selectedLeaveAccount: {},
                showAccountAddEdit: false,
                showDeleteAccount: false,
                showLeaveAccount: false,
            }
        },
        created: function() {
            HTTP.get('/api/v1/u/' + this.$route.params.userName)
                .then(response => {
                    this.user = response.data;
                    this.accounts = response.data.accounts;
                })
                .catch(e => {
                    this.errors.push(e)
                });
        },
        methods: {
            onDeleteAccountCancel: function() {
                this.showDeleteAccount = false;
            },

            onDeleteAccountConfirm: function() {
                console.log('onDeleteAccountConfirm ', this.selectedDeleteAccount);

                HTTP.delete('/api/v1/account/' + this.selectedDeleteAccount.id)
                    .then(response => {
                        console.log('response.data ', response.data);
                        this.accounts = _.reject(this.accounts, function(acct) {
                            return +acct.id === +response.data.id;
                        });
                    })
                    .catch(e => {
                        this.errors.push(e)
                    });
            },

            onLeaveAccountCancel: function() {
                this.showLeaveAccount = false;
            },

            onLeaveAccountConfirm: function() {
                console.log('onLeaveAccountConfirm ', this.selectedLeaveAccount);

                HTTP.post('/api/v1/account/' + this.selectedLeaveAccount.id + '/leave')
                    .then(response => {
                        console.log('response.data ', response.data);
                        this.accounts = _.reject(this.accounts, function(acct) {
                            return +acct.id === +response.data.id;
                        });
                    })
                    .catch(e => {
                        this.errors = e.response.data.errors;
                    });
            },

            leaveAccount: function(acct) {
                this.selectedLeaveAccount = acct;
                this.showLeaveAccount = true;
                console.log('removeServerEvent ', acct);
            },


            removeAccount: function(acct) {
                this.selectedDeleteAccount = acct;
                this.showDeleteAccount = true;
                console.log('removeServerEvent ', acct);
            },

            showAddEditAccount: function(acct) {
                if (acct) {
                    this.selectedAccountForAddEdit = _.clone(acct);
                    this.selectedAccountForAddEdit.actionType = 'Edit';
                } else {
                    this.selectedAccountForAddEdit = {
                        actionType: 'Add',
                        name: undefined
                    };
                }

                this.showAccountAddEdit = !this.showAccountAddEdit;
                console.log('showAccountAddEdit ', this.selectedAccountForAddEdit, this.showAccountAddEdit);
            },

            onAccountChanged: function(responseData) {
                _.each(this.accounts, function(acct) {
                    if (acct.id === responseData.record.id) {
                        acct.name = responseData.record.name;
                    }
                }, this);

                sessionStorage.setItem('accounts', JSON.stringify(responseData.accounts));
            },

            onAccountAdded: function(responseData) {
                this.accounts.push(responseData.record);
                sessionStorage.setItem('accounts', JSON.stringify(responseData.accounts));
            }
        },
        computed: {
        }
    }
</script>
