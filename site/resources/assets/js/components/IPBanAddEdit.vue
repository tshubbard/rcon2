<template>
    <md-dialog
            :md-active.sync="showDialog"
            aria-label="Add/Edit IP Ban Dialog"
            @md-opened="onDialogOpened"
            @md-closed="onDialogClosed"
            class="add-edit-ipban col-8">

        <md-toolbar>
            <div class="md-toolbar-tools">
                <h3>
                    {{addEditLabel}} IP Ban
                    <md-button class="md-icon-button close-button" @click.stop="showDialog = false">
                        <i class="material-icons clickable" aria-label="Close dialog">clear</i>
                    </md-button>
                </h3>
            </div>
        </md-toolbar>
        <md-dialog-content>
            <form name="addEditIPBanActionForm">
                <errors-view :errors="errors"></errors-view>

                <div class="form-group row">
                    <label for="ipban_name" class="col-md-3 col-form-label">IP Ban Name</label>
                    <input id="ipban_name" type="text" class="col-sm-6 form-control"
                           name="ipban_name" v-model="item.name" required>
                </div>
                <div class="form-group row">
                    <label for="ipban_ipaddress" class="col-sm-3 col-form-label">IP Address</label>
                    <input id="ipban_ipaddress" class="form-control col-sm-6" type="text"
                           name="ipban_ipaddress" v-model="item.ipaddress" required>
                </div>
            </form>
        </md-dialog-content>

        <md-divider class="mt-4"></md-divider>

        <md-dialog-actions layout="row" layout-align="end">
            <div class="col-md-4 pl-0">
                <md-button class="md-raised md-accent" v-if="item.id != null" v-on:click="deleteIPBan">
                    Delete This IP Ban
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
                md-title="Delete IP Ban?"
                v-model="item"
                md-content="Are you sure you wish to delete this IP Ban?"
                md-confirm-text="Delete IP Ban"
                md-cancel-text="Cancel"
                @md-cancel="onDeleteIPBanCancel"
                @md-confirm="onDeleteIPBanConfirm"
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
            'ipbanData',
            'visible'
        ],
        data: function() {
            return {
                addEditLabel: '',
                errors: [],
                item: {},
                showConfirmDialog: false
            }
        },
        methods: {
            saveAddEditIPBanDialog: function() {
                let url = HTTP.buildUrl('ipban');
                let payload = _.pick(this.item, 'name', 'ipaddress');
                payload['server_id'] = sessionStorage.getItem('selected_server_id');
                let method;
                let eventName;

                if (this.item.id == null) {
                    method = 'post';
                    eventName = 'ipban-added';
                } else if (this.item.delete) {
                    method = 'delete';
                    url += '/' + this.item.id;
                    eventName = 'ipban-deleted';
                } else {
                    method = 'put';
                    url += '/' + this.item.id;
                    eventName = 'ipban-changed';
                }

                HTTP[method](url, payload)
                    .then(response => {
                        this.$emit(eventName, _.clone(response.data));
                        this.isSaving = false;
                        this.showConfirmDialog = false;
                        this.showDialog = false;
                    })
                    .catch(e => {
                        HTTP.logError(url, e);

                        this.errors.push(e);
                    });
            },

            deleteIPBan: function() {
                this.showConfirmDialog = true;
            },

            onDeleteIPBanCancel: function() {
                this.showConfirmDialog = false;
            },

            onDeleteIPBanConfirm: function() {
                this.item.delete = true;
                this.checkForm();
            },

            checkForm: function(evt) {
                if(evt != null)
                    evt.preventDefault();

                this.errors = [];

                if (!this.item.name) {
                    this.errors.push('Name is required');
                }
                if (!this.item.ipaddress) {
                    this.errors.push('IP Address is required');
                }

                if (!this.errors.length) {
                    this.saveAddEditIPBanDialog();
                }
            },

            onDialogOpened: function() {
                this.errors = [];
                this.item = _.clone(this.ipbanData);

                this.addEditLabel = this.item.id ? 'Edit' : 'Add';

                this.item.is_active = !!this.item.is_active;
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
