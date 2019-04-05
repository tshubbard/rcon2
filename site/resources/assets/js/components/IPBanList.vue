<template>
    <div class="admin servers m-3 record">
        <h3 class="title">
            IP Ban Management
            <md-button class="md-raised md-primary float-right" v-on:click="editIPBan({})">Add New IP Ban</md-button>
        </h3>

        <div class="container-fluid">
            <div class="row record-body">
                <table class="table table-hover">
                    <thead>
                        <th class="text-center">Name</th>
                        <th class="text-center">IP Address</th>
                    </thead>
                    <tbody>
                        <tr v-for="row in ipbans" v-on:click="editIPBan(row)">
                            <td>{{row.name}}</td>
                            <td>{{row.ipaddress}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <IPBanAddEdit
                :visible="showIPBanAddEdit"
                :ipbanData="selectedIPBan"
                v-on:ipban-added="onIPBanAdd"
                v-on:ipban-changed="onIPBanChange"
                v-on:ipban-deleted="onIPBanDelete"
                @close="showIPBanAddEdit = false">

        </IPBanAddEdit>
    </div>
</template>
<script>
    import {HTTP} from '../app';
    import IPBanAddEdit from './IPBanAddEdit';

    export default {
        components: {
            IPBanAddEdit
        },
        data: function() {
            return {
                ipbans: {},
                showIPBanAddEdit: false,
                selectedIPBan: {},
                errors: []
            }
        },
        created: function() {
            let url = HTTP.buildUrl('ipbans');

            HTTP.get(url)
                .then(response => {
                    this.ipbans = response.data;
                })
                .catch(e => {
                    HTTP.logError(url, e);

                    this.errors.push(e)
                });
        },
        methods: {
            editIPBan: function(ipbanData){
                this.selectedIPBan = ipbanData;
                this.showIPBanAddEdit = true;
            },
            onIPBanAdd: function(newIPBan) {
                this.ipbans.push(newIPBan.data);
            },
            onIPBanChange: function(changedIPBan) {
                if(changedIPBan.success == null || !changedIPBan.success)
                    return false;

                _.each(this.ipbans, function(ipban) {
                    if (ipban.id === changedIPBan.data.id) {
                        Object.assign(ipban, changedIPBan.data);
                    }
                }, this);
            },
            onIPBanDelete: function(deletedIPBan) {
                if(deletedIPBan.success == null || !deletedIPBan.success)
                    return false;

                this.ipbans = _.reject(this.ipbans, function(ipban) {
                    return ipban.id === deletedIPBan.data.id;
                });
            }
        },
        computed: {
        }
    }
</script>
