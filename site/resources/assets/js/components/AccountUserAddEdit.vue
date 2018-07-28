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

                <div class="form-group row">
                    <label for="user_name" class="col-md-3 col-form-label" v-if="!item.id">Email</label>
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
                           v-if="item.id != null && item.owner_id === $parent.user.id"
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
                        @click.stop="checkForm"
                        class="md-raised md-primary">
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
            'userData',
            'visible'
        ],
        data: function() {
            return {
                addEditLabel: '',
                errors: [],
                isSaving: false,
                item: {},
                showConfirmDialog: false,
            }
        },
        created: function() {
            this.isSaving = false;
        },
        methods: {
            onDialogOpened: function(){
                this.errors = [];
                this.item = _.clone(this.userData);
                this.addEditLabel = this.item.id ? 'Edit' : 'Add';

                console.log('this.item: ', this.item);

            },

            onDialogClosed: function(){
                this.errors = [];
            },

            saveAddEditAccountDialog: function() {
                let url = HTTP.buildUrl('accounts');
                let eventName;
                let payload = _.pick(this.item, 'name');
                let method;

                if (!this.item.id) {
                    method = 'post';
                    eventName = 'account-user-added';
                    payload.owner_id = this.$parent.user.id;
                } else if (this.item.delete) {
                    method = 'delete';
                    url += '/' + this.item.id;
                    eventName = 'account-user-deleted';
                } else {
                    method = 'put';
                    url += '/' + this.item.id;
                    eventName = 'account-user-changed';
                }

                HTTP[method](url, payload)
                    .then(response => {
                        this.$emit(eventName, _.clone(response.data));
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

            checkForm: function(evt) {
                if (evt) {
                    evt.preventDefault();
                    evt.stopImmediatePropagation();
                }

                if (!this.isSaving) {
                    this.isSaving = true;
                } else {
                    return;
                }

                this.errors = [];

                if(!this.item.name) {
                    this.errors.push('Name is required');
                }

                if(!this.errors.length) {
                    this.saveAddEditAccountDialog();
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
