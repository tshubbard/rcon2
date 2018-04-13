<template>
    <div class="servers record">
        <h3 class="title">
            Server Management
        </h3>

        <div class="container-fluid">
            <div class="row record-body">
                <table class="admin-servers-table table table-hover">
                    <thead>
                        <th class="text-center">ID</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Host</th>
                        <th class="text-center">Player Slots</th>
                        <th class="text-center">Timezone</th>
                    </thead>
                    <tbody>
                        <tr v-for="row in servers" v-on:click="editServer(row)">
                            <td class="text-center">{{row.id}}</td>
                            <td>{{row.name}}</td>
                            <td>{{row.host}}:{{row.port}}</td>
                            <td class="text-center">{{row.max_players}}</td>
                            <td>{{row.timezone}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <server-add-edit
                :visible="showServerAddEdit"
                :serverData="selectedServer"
                @close="showServerAddEdit = false">

        </server-add-edit>
    </div>
</template>
<script>
    import ServerAddEdit from './ServerAddEdit';

    export default {
	    components: {
            ServerAddEdit
        },
        data: function() {
            return {
                servers: [],
                showServerAddEdit: false,
                selectedServer: {}
            }
        },
        created: function() {
            $.get('/api/v1/admin/servers', function(data) {
                console.log('data: ', data);

                this.servers = data.servers;

            }.bind(this), 'json');
        },
        methods: {
            'editServer': function(serverData){
                if(serverData.disabled)
                    serverData.active = false;
                else
                    serverData.active = true;

                this.selectedServer = serverData;
                this.showServerAddEdit = true;
            }
        },
        computed: {
        }
    }
</script>
