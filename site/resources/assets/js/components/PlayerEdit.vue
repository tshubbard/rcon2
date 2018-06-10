<template>
    <md-dialog
            :md-active.sync="showDialog"
            aria-label="Edit Player Dialog"
            @md-opened="onDialogOpened"
            class="edit-player col-8">

        <md-toolbar>
            <div class="md-toolbar-tools">
                <h3>
                    Edit Player - {{item.username}} / Why is this box so fucking big?
                    <md-button class="md-icon-button close-button" v-on:click="showDialog = false">
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

        <md-dialog-actions layout="row" layout-align="end">
            <div class="col-md-4 pl-0">
                <md-button class="md-raised md-accent" v-if="item.id != null" v-on:click="deletePlayer">Delete This Player</md-button>
            </div>
            <div class="col-md-8 text-right">
                <md-button class="md-raised md-primary" v-on:click="showDialog = false" type="button">
                    Close
                </md-button>
            </div>
        </md-dialog-actions>

    </md-dialog>
</template>

<script>
    export default {
        props: [
            'playerData',
            'visible'
        ],
        data: function() {
            return {
                item: {}
            }
        },
        methods: {
            saveEditPlayerDialog: function() {
                let url = '/api/v1/player';
                let payload = {};
                let method;

                if(this.item.delete != null)
                {
                    method = 'DELETE';
                    url += '/' + this.item.id;
                }

                $.ajax({'type': method, 'url': url, 'data': payload, 'success': function(data){
                    this.$emit('player-change', data.data);
                    this.showDialog = false;
                }.bind(this), 'dataType': 'json'});
            },
            deletePlayer: function(){
                swal({
                    title: 'Are you sure?',
                    text: 'Are you sure you wish to delete this player and all their associated data?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function(result){
                    if (result.value) {
                        this.item.delete = true;
                        this.saveEditPlayerDialog();
                    }
                }.bind(this));
            },
            onDialogOpened: function(){
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
