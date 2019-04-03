<template>
    <div class="admin m-3 record">
        <h3 class="title">
            Chat Log
        </h3>

        <div class="container-fluid">
            <div class="row record-body">
                <md-table class="servers-list-table table table-hover">
                    <md-table-row>
                        <md-table-head class="text-center">Time</md-table-head>
                        <md-table-head class="text-center">Username</md-table-head>
                        <md-table-head class="text-center">Message</md-table-head>
                    </md-table-row>
                    <md-table-row v-for="row in chat" :key="row.id">
                        <md-table-cell class="text-center" md-label="Time" nowrap>{{row.added_on}}</md-table-cell>
                        <md-table-cell md-label="Username">{{row.username}}</md-table-cell>
                        <md-table-cell md-label="Message">{{row.message}}</md-table-cell>
                    </md-table-row>
                </md-table>
            </div>
            <div class="row">
                <div class="md-layout md-gutter md-alignment-top-center" style="width: 100%">
                    <div class="md-layout-item md-size-15"><a v-on:click="changePage('down')"><md-icon class="md-size-3x">navigate_before</md-icon></a></div>
                    <div class="md-layout-item md-size-60">&nbsp;</div>
                    <div class="md-layout-item md-size-15"><a v-on:click="changePage('up')"><md-icon class="md-size-3x">navigate_next</md-icon></a></div>
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
                chat: [],
                page: 1
            }
        },
        created: function() {
            this.loadMessages();
        },
        methods: {
            loadMessages() {
                let serverId = sessionStorage.getItem('selected_server_id');
                let url = HTTP.buildUrl('chat/' + serverId + '/' + this.page);

                HTTP.get(url)
                    .then(response => {
                        this.chat = response.data.messages;
                    })
                    .catch(e => {
                        HTTP.logError(url, e);

                        this.errors.push(e)
                    });
            },
            changePage(direction)
            {
                let curpage = this.page;

                if(direction == 'up' && this.chat.length >= 100)
                    this.page++;
                else if(direction == 'down' && this.page != 1)
                    this.page--;

                if(curpage != this.page)
                    this.loadMessages();
            }
        },
        computed: {
        }
    }
</script>
