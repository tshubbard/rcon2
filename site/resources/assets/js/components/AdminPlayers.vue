<template>
    <div class="servers record">
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
                        <th class="text-center">Last Login</th>
                    </thead>
                    <tbody>
                        <tr v-for="row in players" v-on:click="editPlayer(row)">
                            <td class="text-center">{{row.steam_id}}</td>
                            <td class="text-center">{{row.short_id}}</td>
                            <td>{{row.username}}</td>
                            <td>{{row.last_login}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <player-edit
                :visible="showPlayerEdit"
                :playerData="selectedPlayer"
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
        data: function() {
            return {
                players: {},
                showPlayerEdit: false,
                selectedPlayer: {}
            }
        },
        created: function() {
            let selected_server_id = sessionStorage.getItem('selected_server_id');

            $.get(('/api/v1/players/' + selected_server_id), function(data) {
                //console.log('data: ', data);

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
