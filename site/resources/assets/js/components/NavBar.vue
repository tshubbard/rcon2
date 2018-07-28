<template>
    <div>
        <nav-admin v-if="zone === 'admin'" :authedUserData="authUser"></nav-admin>
        <nav-authed v-if="zone === 'authed'" :authedUserData="authUser"></nav-authed>
        <nav-home v-if="zone === 'home'"></nav-home>
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
                authUser: {}
            }
        },
        created: function() {
            console.log('NAVBAR this: ', this);

            this.authUser = _.clone(JSON.parse(sessionStorage.getItem('me')));
        },
        methods: {
        },
        computed: {
            zone() {
                console.log('zone ', this.$route.meta.zone);

                return this.$route.meta.zone;
            },
        }
    }
</script>
