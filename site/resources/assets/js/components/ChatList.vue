<template>
    <div class="admin m-3 record">
        <h3 class="title">
            Chat Log
        </h3>

        <div class="container-fluid">
            <div class="row record-body">
                <md-table md-card class="servers-list-table table table-hover">
                    <thead>
                        <md-table-head class="text-center">Time</md-table-head>
                        <md-table-head class="text-center">Username</md-table-head>
                        <md-table-head class="text-center">Message</md-table-head>
                    </thead>
                    <tbody>
                        <md-table-row v-for="row in chat" :key="row.id">
                            <md-table-cell class="text-center" md-label="Time" nowrap>{{row.added_on}}</md-table-cell>
                            <md-table-cell md-label="Username">{{row.username}}</md-table-cell>
                            <md-table-cell md-label="Message">{{row.message}}</md-table-cell>
                        </md-table-row>
                    </tbody>
                </md-table>
            </div>
        </div>
    </div>
</template>
<script>
    import {HTTP} from '../app';

    export default {
        data: function() {
            return {
                chat: []
            }
        },
        created: function() {
            let serverId = sessionStorage.getItem('selected_server_id');
            let url = HTTP.buildUrl('chat/' + serverId);

            HTTP.get(url)
                .then(response => {
                    this.chat = response.data.messages;
                })
                .catch(e => {
                    HTTP.logError(url, e);

                    this.errors.push(e)
                });
        },
        methods: {
        },
        computed: {
        }
    }
</script>
