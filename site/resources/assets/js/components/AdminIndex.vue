<template>
    <div class="admin m-3 record">
        <h3 class="title">
            Admin Dashboard
        </h3>

        <div class="container-fluid">
            <div class="row record-body">
                <div class="col-md-6">
                    <h5>Admin Info</h5>
                    <div>
                        Hello {{ data.thisUser.name }}
                    </div>
                </div>
                <div class="col-md-6">
                    <h5></h5>
                    <div>

                    </div>
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
                data: {
                    thisUser: {
                        name: ''
                    }
                }
            }
        },
        created: function() {
            let url = HTTP.buildUrl('admin');

            HTTP.get(url)
                .then(response => {
                    console.log('data: ', response.data);

                    this.data = response.data;
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
