<template>
    <div class="user m-3 record">
        <h3 class="title">
            <span class="name">{{ user.name }}</span>
        </h3>

        <div class="container-fluid">
            <div class="row record-body">
                <div class="col-md-6">
                    <h5>User Info</h5>
                    <div>
                        <label for="name">Email: </label>
                        <span class="name" id="name" name="name">{{ user.email }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Accounts Info</h5>
                    <div>
                        <ul>
                            <li v-for="acct in accounts">
                                <router-link class="nav-link" :to="'/a/' + acct.name">
                                    {{acct.name}}
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
                user: [],
                accounts: []
            }
        },
        created: function() {
            $.get('/api/v1/u/' + this.$route.params.userName, function(data) {
                console.log('data: ', data);

                this.user = data;
                this.accounts = data.accounts;

            }.bind(this), 'json');
        },
        methods: {
        },
        computed: {
        }
    }
</script>
