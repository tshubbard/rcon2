<template>
    <div class="user m-3 record">
        <h3 class="title">
            <span class="name">{{ user.name }}</span>
        </h3>

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

                    <h5>
                        Accounts Info
                        <md-button class="md-raised md-primary zf-icon-button zf-top-minus-10"
                                   @click.stop="showAddEditAccount()">
                            <md-icon>add</md-icon>
                        </md-button>
                    </h5>

                    <div>
                        <ul>
                            <li v-for="acct in accounts">
                                <router-link class="nav-link" :to="'/a/' + acct.name">
                                    {{acct.name}}
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <account-add-edit
                :visible="showAccountAddEdit"
                :accountData="selectedAccountForAddEdit"
                v-on:account-change="onAccountChange"
                @close="showAccountAddEdit = false">
        </account-add-edit>
    </div>
</template>
<script>
    import AccountAddEdit from './AccountAddEdit';

    export default {
        components: {
            AccountAddEdit
        },
        data: function() {
            return {
                user: [],
                accounts: [],
                showAccountAddEdit: false,
                selectedAccountForAddEdit: {},
            }
        },
        created: function() {
            $.get('/api/v1/u/' + this.$route.params.userName, function(data) {
                console.log('data: ', data);

                this.user = data;
                this.accounts = data.accounts;

            }.bind(this), 'json');
        },
        methods: {

            showAddEditAccount: function(acct) {
                if (acct) {
                    this.selectedAccountForAddEdit = _.clone(event);
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

            onAccountChange: function(evt) {
                debugger;
            }
        },
        computed: {
        }
    }
</script>
