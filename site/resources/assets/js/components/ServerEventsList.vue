<template>
    <div>
        <div class="server-events m-3 record">
            <h3 class="title">
                Server Events
            </h3>

            <div class="container-fluid">
                <div class="row record-body">
                    <table class="admin-users-table table">
                        <thead>
                        <th class="text-center">Event Name</th>
                        <th class="text-center">Votes</th>
                        <th class="text-center">Type</th>
                        </thead>
                        <tbody>
                        <tr v-for="evt in serverEvents" :key="evt.id">
                            <td>
                                <router-link class="nav-link" :to="'/serverEvent/' + evt.id">
                                    {{evt.name}}
                                </router-link>
                            </td>
                            <td class="text-center">{{evt.votes}}</td>
                            <td class="text-center">{{evt.event_type}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {HTTP} from '../app';

    export default {
        data: function() {
            return {
                errors: [],
                serverEvents: []
            }
        },
        created: function() {
            console.log('this: ', this);
            let url = HTTP.buildUrl('serverEvents');
            HTTP.get(url)
                .then(response => {
                    this.serverEvents = response.data;

                    console.log('this.serverEvents: ', this.serverEvents);

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
