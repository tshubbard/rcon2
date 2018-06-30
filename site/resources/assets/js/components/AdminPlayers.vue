<template>
    <div class="players m-3 record">
        <h3 class="title" v-if="!currentonly">
            Players
        </h3>

        <div class="container-fluid">
            <div class="row record-body">
                <table class="admin-servers-table table table-hover">
                    <thead>
                        <th class="text-center">Steam ID</th>
                        <th class="text-center">Combat ID</th>
                        <th class="text-center">Name</th>
                        <th class="text-center" v-if="!currentonly">Last Login</th>
                    </thead>
                    <tbody>
                        <tr v-for="row in players" v-on:click="editPlayer(row)">
                            <td class="text-center">{{row.steam_id}}</td>
                            <td class="text-center">{{row.short_id}}</td>
                            <td>{{row.username}}</td>
                            <td v-if="!currentonly">{{row.last_login}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <player-edit
                :visible="showPlayerEdit"
                :playerData="selectedPlayer"
                :currentonly="currentonly"
                @close="showPlayerEdit = false">
        </player-edit>
    </div>
</template>
<script>
    import PlayerEdit from './PlayerEdit';

    export default {
	    components: {
            PlayerEdit
        },
        props: [
            'currentonly'
        ],
        data: function() {
            return {
                players: {},
                showPlayerEdit: false,
                selectedPlayer: {}
            }
        },
        created: function() {
            let selected_server_id = sessionStorage.getItem('selected_server_id');

            let endpoint = (this.currentonly != null && this.currentonly) ? 'currentplayers' : 'players';

            $.get(('/api/v1/' + endpoint + '/' + selected_server_id), function(data) {
                this.players = data.players;

            }.bind(this), 'json');
        },
        methods: {
            editPlayer: function(playerData){
                this.selectedPlayer = playerData;
                this.showPlayerEdit = true;
            },
            onPlayerChange: function(changedPlayer) {
                if(changedPlayer.deleted != null && changedPlayer.deleted)
                {
                    if(this.players[changedPlayer.id] != null)
                        delete this.players[changedPlayer.id];
                }
            }
        }
    }
</script>
