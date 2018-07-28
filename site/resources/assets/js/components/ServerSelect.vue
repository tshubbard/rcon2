<template>
    <div class="navbar-nav mr-auto server-select">
        <div class="single-server-name" v-if="servers.length === 1" :title="selectedServer.name">
            Server: {{serverName}}
        </div>
        <div v-if="servers.length > 1">
            <md-field>
                <label for="selected-server">Server Select:</label>
                <md-select id="selected-server" name="selected-server"
                           v-model="selectedServerId"
                           @input="updateSelectedServer">
                    <md-option v-for="server in servers"
                               :key="server.id"
                               :value="server.id">
                        {{server.name}}
                    </md-option>
                </md-select>
            </md-field>
        </div>
    </div>
</template>
<script>
    import {HTTP} from '../app';

    export default {
        data: function() {
            return {
                selectedServer: {},
                selectedServerId: 0,
                servers: []
            }
        },
        created: function() {
            let serverId = +this.$route.params.serverId;
            let serverUrl = HTTP.buildUrl('servers');
            this.$bus.$on('get-selected-server', this.onGetSelectedServer, this);

            HTTP.get(serverUrl)
                .then(response => {
                    this.servers = response.data;

                    if (serverId) {
                        this.selectedServer = _.find(this.servers, function(server) {
                            return server.id === serverId;
                        });
                    } else {
                        if(this.servers.length > 0) {
                            this.selectedServer = this.servers[0];
                        }

                        if(this.servers.length === 1) {
                            this.serverName = this.selectedServer.name.length > 25 ?
                                this.selectedServer.name.substr(0, 25) + '...' :
                                this.selectedServer.name;
                        }
                    }

                    this.selectedServerId = this.selectedServer.id;

                    sessionStorage.setItem('selected_server_id', this.selectedServerId);

                    this.$bus.$emit('server-changed', this.selectedServer);
                })
                .catch(e => {
                    HTTP.logError(serverUrl, e);

                    this.errors.push(e)
                });
            console.log('ServerSelect this: ', this.servers);
        },
        methods: {
            /**
             * Updates the selected server when the dropdown is changed
             */
            updateSelectedServer: function(serverId) {
                this.selectedServer = _.find(this.servers, function(server) {
                    return server.id === serverId;
                });

                this.selectedServerId = this.selectedServer.id;

                console.log('server id set to ', this.selectedServerId);

                sessionStorage.setItem('selected_server_id', this.selectedServerId);

                this.$bus.$emit('server-changed', this.selectedServer);
            },
            onGetSelectedServer: function() {
                this.$bus.$emit('server-changed', this.selectedServer);
            }
        },
        watch: {
        },
        computed: {
        }
    }
</script>
