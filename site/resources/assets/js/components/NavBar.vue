<template>
    <div>
        <nav-admin v-if="showAdmin" :authedUserData="authUser"></nav-admin>
        <nav-authed v-if="showAuthed" :authedUserData="authUser"></nav-authed>
        <nav-home v-if="showHome"></nav-home>
    </div>
</template>
<script>
    import NavAdmin from './NavAdmin';
    import NavAuthed from './NavAuthed';
    import NavHome from './NavHome';

    export default {
        components: {
            NavAdmin,
            NavAuthed,
            NavHome
        },
        data: function() {
            return {
                authUser: {},
                showAdmin: false,
                showAuthed: false,
                showHome: false,
            }
        },
        created: function() {
            console.log('NAVBAR this: ', this);

            this.authUser = _.clone(JSON.parse(sessionStorage.getItem('me')));

            console.log('this.authUser: ', this.authUser);

            if (!this.authUser) {
                this.$bus.$on('user-authed', this.onUserAuthed, this);
                this.showHome = true;
            } else {
                this.onUserAuthed();
            }
        },
        methods: {
            onUserAuthed: function(userData) {
                this.authUser = this.authUser || _.clone(userData);
                if (this.zone) {
                    if (this.zone === 'authed') {
                        this.showHome = false;
                        this.showAuthed = true;
                    } else if (this.zone === 'admin') {
                        this.showHome = false;
                        this.showAdmin = true;
                    }
                }
            }
        },
        computed: {
            zone() {
                console.log('zone ', this.$route.meta.zone);

                return this.$route.meta.zone;
            },
        }
    }
</script>
