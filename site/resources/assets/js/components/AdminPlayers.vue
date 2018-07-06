<template>
    <div class="record" v-bind:class="[ !currentOnly ? 'players m-3' : '' ]">
        <div v-if="!currentOnly">
            <h3 class="title">
                Players
            </h3>

            <div class="container-fluid">
                <div class="row record-body">
                    <table class="admin-servers-table table table-hover">
                        <thead>
                        <th class="text-center">Steam ID</th>
                        <th class="text-center">Combat ID</th>
                        <th class="text-center">Name</th>
                        <th class="text-center" v-if="!currentOnly">Last Login</th>
                        </thead>
                        <tbody>
                        <tr v-for="row in players" v-on:click="editPlayer(row)">
                            <td class="text-center">{{row.steam_id}}</td>
                            <td class="text-center">{{row.short_id}}</td>
                            <td>{{row.username}}</td>
                            <td v-if="!currentOnly">{{row.last_login}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-if="currentOnly">
           <md-list class="md-double-line zf-list players-list">
                <md-list-item v-for="row in players" :key="row.id">
                    <md-icon class="md-primary">person</md-icon>
                    <div class="md-list-item-text">
                        <span>
                            {{row.username}}
                        </span>
                        <span>
                            Max Players: {{row.short_id}}
                        </span>
                    </div>
                    <md-button class="md-icon-button md-list-action"
                               @click.stop="editPlayer(row)">
                        <md-tooltip>User Info</md-tooltip>
                        <md-icon>info</md-icon>
                    </md-button>
                    <md-button class="md-icon-button md-list-action"
                               @click.stop="deletePlayer(row)">
                        <md-tooltip>Delete User Info</md-tooltip>
                        <md-icon>delete</md-icon>
                    </md-button>
                </md-list-item>
            </md-list>
        </div>

        <player-edit
                :visible="showPlayerEdit"
                :playerData="selectedPlayer"
                :currentOnly="currentOnly"
                v-on:player-change="onPlayerChange"
                @close="showPlayerEdit = false">
        </player-edit>
    </div>
</template>
<script>
    import {HTTP} from '../app';
    import PlayerEdit from './PlayerEdit';

    export default {
        components: {
            PlayerEdit
        },
        props: [
            'currentOnly'
        ],
        data: function() {
            return {
                errors: [],
                players: {},
                showPlayerEdit: false,
                selectedPlayer: {}
            }
        },
        created: function() {
            let serverId = sessionStorage.getItem('selected_server_id');
            let url = '/api/v1/players/' + serverId;

            if (this.currentOnly) {
                url += '/current';
            }

            HTTP.get(url)
                .then(response => {
                    this.players = response.data.players;
                })
                .catch(e => {
                    this.errors.push(e)
                });
        },
        methods: {
            editPlayer: function(playerData) {
                this.selectedPlayer = playerData;
                this.showPlayerEdit = true;
            },
            onPlayerChange: function(changedPlayer) {
                if (changedPlayer.deleted_at) {
                    this.players = _.reject(this.players, function(player) {
                        return player.id === changedPlayer.id;
                    }, this);
                }
            },

            deletePlayer: function(playerData) {

            }
        }
    }
</script>
