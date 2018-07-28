<template>
    <div>
        <div v-if="servers.length === 1">
            Server: {{servers[0].name}}
        </div>
        <div v-if="servers.length > 1">
            <md-field>
                <label for="selected-server">Servers:</label>
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
        props: [
            'serversData',
            'selectedServerData'
        ],
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
            HTTP.get(serverUrl)
                .then(response => {
                    this.servers = response.data;

                    if (serverId) {
                        this.selectedServer = _.find(this.servers, function(server) {
                            return server.id === serverId;
                        })
                    } else {
                        if(this.servers.length > 0) {
                            this.selectedServer = this.servers[0];
                        }
                    }

                    this.selectedServerId = this.selectedServer.id;

                    sessionStorage.setItem('selected_server_id', this.selectedServerId);

                    console.log('user/servers data ', response);
                    console.log('this.servers ', this.servers);
                    console.log('selectedServer: ', this.selectedServer);
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

                this.$emit('server-changed', this.selectedServer);
            },
        },
        watch: {
            serversData: function(data) {
                this.servers = _.clone(data);
            },
            selectedServerData: function(data) {
                this.selectedServer = _.clone(data);
                this.selectedServerId = this.selectedServer.id;
            }
        },
        computed: {
        }
    }
</script>
