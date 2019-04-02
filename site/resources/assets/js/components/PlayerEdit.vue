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
                    <md-button class="md-icon-button close-button mr-2 mt-2" @click.stop="showDialog = false">
                        <i class="material-icons clickable pt-2" aria-label="Close dialog">clear</i>
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
                        <div class="col-5">
                            Last Login:
                        </div>
                        <div class="col-7">
                            {{item.last_login}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            Steam Profile:
                        </div>
                        <div class="col-7">
                            <a :href="item.steam_profile" target="_new">{{item.steamName}}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            Steam ID:
                        </div>
                        <div class="col-7">
                            {{item.steam_id}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            Combat ID:
                        </div>
                        <div class="col-7">
                            {{item.short_id}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            VAC Bans:
                        </div>
                        <div class="col-7">
                            {{item.steam_vacban_count}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            Game Bans:
                        </div>
                        <div class="col-7">
                            {{item.steam_gameban_count}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            Days Since Last Ban:
                        </div>
                        <div class="col-7">
                            {{item.steam_days_since_last_ban}}
                        </div>
                    </div>
                </div>
            </div>
        </md-dialog-content>

        <md-divider class="my-2"></md-divider>

        <div class="container-fluid mb-2">
            <div class="row">
                <div class="col">
                    <div class="pl-0">
                        <md-button class="md-raised md-accent" v-if="!currentOnly && item.id != null" @click.stop="deletePlayer">Delete This Player</md-button>
                        <md-button class="md-raised md-accent" v-if="currentOnly" @click.stop="kickPlayer">Kick Player</md-button>
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
    import format from 'date-fns/format';

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

                let url = HTTP.buildUrl('players/' + this.item.id);
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
                        HTTP.logError(url, e);

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
                let tmp = _.clone(this.playerData);

                if(tmp.steam_profile !== null) {
                    let steamUrl = tmp.steam_profile.split('/');
                    tmp.steamName = steamUrl[steamUrl.length - 1] || steamUrl[steamUrl.length - 2];
                }

                tmp.last_login = format(tmp.last_login, 'MM-DD-YYYY');

                this.item = tmp;
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
