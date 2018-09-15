<template>
    <div class="admin servers m-3 record">
        <h3 class="title">
            Server Management
            <md-button class="md-raised md-primary float-right" v-on:click="editServer({})">Add New Server</md-button>
        </h3>

        <div class="container-fluid">
            <div class="row record-body">
                <table class="servers-list-table table table-hover">
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
                v-on:server-added="onServerAdd"
                v-on:server-changed="onServerChange"
                v-on:server-deleted="onServerDelete"
                @close="showServerAddEdit = false">

        </server-add-edit>
    </div>
</template>
<script>
    import {HTTP} from '../app';
    import ServerAddEdit from './ServerAddEdit';

    export default {
        components: {
            ServerAddEdit
        },
        data: function() {
            return {
                servers: {},
                showServerAddEdit: false,
                selectedServer: {}
            }
        },
        created: function() {
            let url = HTTP.buildUrl('servers');

            HTTP.get(url)
                .then(response => {
                    console.log('server data: ', response);
                    this.servers = response.data;
                })
                .catch(e => {
                    HTTP.logError(url, e);

                    this.errors.push(e)
                });
        },
        methods: {
            editServer: function(serverData){
                if(serverData.disabled)
                    serverData.active = false;
                else
                    serverData.active = true;

                this.selectedServer = serverData;
                this.showServerAddEdit = true;
            },
            onServerAdd: function(newServer) {
                this.servers.push(newServer.data);
            },
            onServerChange: function(changedServer) {
                if(changedServer.success == null || !changedServer.success)
                    return false;

                _.each(this.servers, function(server) {
                    if (server.id === changedServer.data.id) {
                        Object.assign(server, changedServer.data);
                    }
                }, this);
            },
            onServerDelete: function(deletedServer) {
                if(deletedServer.success == null || !deletedServer.success)
                    return false;

                this.servers = _.reject(this.servers, function(server) {
                    return server.id === deletedServer.data.id;
                });
            }
        },
        computed: {
        }
    }
</script>
