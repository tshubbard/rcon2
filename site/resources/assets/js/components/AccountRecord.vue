<template>
    <div class="users record">
        <h3 class="title">
            <span class="name">{{ account.name }}</span>
        </h3>

        <div class="container-fluid">
            <div class="row record-body">
                <div class="col-md-6">
                    <h5>Account Info</h5>
                    <div>
                        <label for="name">Email: </label>
                        <span class="name" id="name" name="name">{{ account }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Users Info</h5>
                    <div>
                        <ul>
                            <li v-for="user in users">
                                <router-link class="nav-link" :to="'/u/' + user.name">
                                    {{user.name}}
                                </router-link>
                            </li>
                        </ul>
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
                account: {},
                users: []
            }
        },
        created: function() {
            $.get('/api/v1/a/' + this.$route.params.accountName, function(data) {
                console.log('data: ', data);

                this.account = data;
                this.users = data.users;

            }.bind(this), 'json');
        },
        methods: {
        },
        computed: {
        }
    }
</script>
