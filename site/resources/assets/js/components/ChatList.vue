<template>
    <div class="admin m-3 record">
        <h3 class="title">
            Chat Log
        </h3>

        <div class="container-fluid">
            <div class="row record-body">
                <table class="servers-list-table table table-hover">
                    <thead>
                        <th class="text-center">Time</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Message</th>
                    </thead>
                    <tbody>
                        <tr v-for="row in chat">
                            <td class="text-center" nowrap>{{row.added_on}}</td>
                            <td>{{row.username}}</td>
                            <td>{{row.message}}</td>
                        </tr>
                    </tbody>
                </table>
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
