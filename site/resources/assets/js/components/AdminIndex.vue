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
            $.get('/api/v1/admin', function(data) {
                console.log('data: ', data);

                this.data = data;

            }.bind(this), 'json');
        },
        methods: {
        },
        computed: {
        }
    }
</script>
