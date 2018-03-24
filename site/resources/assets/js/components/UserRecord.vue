<template>
    <div class="users record">
        <h3 class="title">
            <span class="name">{{ user.name }}</span>
        </h3>

        <div class="record-body">
            <div class="user-name">

                <label for="name">User: </label>
                <span class="name" id="name" name="name">{{ user }}</span>
            </div>
            <div class="user-name">

                <label for="accounts">Accounts: </label>
                <span class="accounts" id="accounts" name="accounts">{{ accounts }}</span>
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        'data': function() {
            return {
                'user': [],
                'accounts': []
            }
        },
        'created': function() {

            $.get('/api/v1/u/' + this.$route.params.userName, function(data) {
                console.log('data: ', data);

                this.user = data;
                this.accounts = data.accounts;

            }.bind(this), 'json');

            console.log('this: ', this);

        },
        'methods': {

        },
        'computed': {

        }
    }
</script>
