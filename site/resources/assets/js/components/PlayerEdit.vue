<template>
    <md-dialog
            :md-active.sync="showDialog"
            aria-label="Edit Player Dialog"
            @md-opened="onDialogOpened"
            class="edit-player">

        <md-toolbar>
            <div class="md-toolbar-tools">
                <h3>
                    Edit Player - {{item.username}}
                    <md-button class="md-icon-button close-button" @click.stop="showDialog = false">
                        <i class="material-icons clickable" aria-label="Close dialog">clear</i>
                    </md-button>
                </h3>
            </div>
        </md-toolbar>
        <md-dialog-content>
            <div class="row">
                <div class="col-4">
                    <img :src="item.steam_avatar_large">
                </div>
                <div class="col-8">
                    <div class="row">
                        <div class="col-4">
                            Last Login:
                        </div>
                        <div class="col-4">
                            {{item.last_login}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            Steam Profile:
                        </div>
                        <div class="col-4">
                            <a :href="item.steam_profile" target="_new">{{item.steam_profile}}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            Steam ID:
                        </div>
                        <div class="col-4">
                            {{item.steam_id}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            Combat ID:
                        </div>
                        <div class="col-4">
                            {{item.short_id}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            VAC Bans:
                        </div>
                        <div class="col-4">
                            {{item.steam_vacban_count}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            Game Bans:
                        </div>
                        <div class="col-4">
                            {{item.steam_gameban_count}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            Days Since Last Ban:
                        </div>
                        <div class="col-4">
                            {{item.steam_days_since_last_ban}}
                        </div>
                    </div>
                </div>
            </div>
        </md-dialog-content>

        <md-divider class="mt-4"></md-divider>

        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col">
                    <div class="pl-0" v-if="!currentOnly">
                        <md-button class="md-raised md-accent" v-if="item.id != null" @click.stop="deletePlayer">Delete This
                            Player
                        </md-button>
                    </div>
                    <div class="pl-0" v-if="currentOnly">
                        <md-button class="md-raised md-accent" @click.stop="kickPlayer">Kick Player</md-button>
                        <md-button class="md-raised md-accent" @click.stop="banPlayer">Ban Player</md-button>
                    </div>
                </div>
                <div class="col text-right">
                    <md-button class="md-raised md-primary" @click.stop="showDialog = false">
                        Close
                    </md-button>
                </div>

            </div>


        </div>

        <md-dialog-confirm
                :md-active.sync="showConfirmDialog"
                :md-title="dialogTitle"
                v-model="item"
                :md-content="dialogContent"
                :md-confirm-text="dialogConfirmText"
                md-cancel-text="Cancel"
                @md-cancel="onConfirmDialogCancel"
                @md-confirm="onConfirmDialogConfirm"
        />

    </md-dialog>
</template>

<script>
    import {HTTP} from '../app';

    export default {
        props: [
            'playerData',
            'visible',
            'currentOnly'
        ],
        data: function() {
            return {
                dialogAction: '',
                dialogTitle: '',
                dialogContent: '',
                dialogConfirmText: '',
                item: {},
                selectedDeletePlayer: {},
                showConfirmDialog: false
            }
        },
        methods: {
            onConfirmDialogCancel: function() {
                this.showConfirmDialog = false;
            },

            onConfirmDialogConfirm: function() {

                let url = '/api/v1/players/' + this.item.id;
                let method = 'delete';

                if (this.dialogAction !== 'delete') {
                    url += '/' + this.dialogAction;
                    method = 'post';
                }

                HTTP[method](url)
                    .then(response => {
                        this.$emit('player-change', _.clone(response.data));
                        this.showDialog = false;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    });
            },

            deletePlayer: function() {
                let name = this.item.username;

                this.dialogAction = 'delete';
                this.dialogTitle = 'Delete Player: ' + name;
                this.dialogContent = 'Are you sure you wish to delete ' + name + ' and all their associated data?';
                this.dialogConfirmText = 'Delete Player';
                this.showConfirmDialog = true;
            },

            kickPlayer: function() {
                let name = this.item.username;

                this.dialogAction = 'kick';
                this.dialogTitle = 'Kick Player: ' + name;
                this.dialogContent = 'Are you sure you wish to kick ' + name + ' from your server?';
                this.dialogConfirmText = 'Yes, Kick Player!';
                this.showConfirmDialog = true;
            },

            banPlayer: function() {
                let name = this.item.username;

                this.dialogAction = 'ban';
                this.dialogTitle = 'Ban Player: ' + name;
                this.dialogContent = 'Are you sure you wish to ban ' + name + ' from your server?';
                this.dialogConfirmText = 'Yes, Ban Player!';
                this.showConfirmDialog = true;
            },

            onDialogOpened: function() {
                this.item = _.clone(this.playerData);
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
