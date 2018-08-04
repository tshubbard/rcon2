<template>
    <div>
        <nav-admin v-if="showAdmin"
                   :authedUserData="authUser"
                   :appNameData="appName">
        </nav-admin>
        <nav-authed v-if="showAuthed"
                    :authedUserData="authUser"
                    :appNameData="appName">
        </nav-authed>
        <nav-home v-if="showHome"
                  :appNameData="appName">
        </nav-home>
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
                appName: 'Rust ReCON',
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
            console.log('this.appName: ', this.appName);

            if (!this.authUser) {
                if (this.zone === 'home') {
                    this.showHome = true;
                }
                this.$bus.$on('user-authed', this.onUserAuthed, this);
            } else {
                this.onUserAuthed();

            }

            console.log(' this.showHome: ',  this.showHome, this.zone);
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
                    } else if (this.zone === 'home') {
                        if (this.authUser && this.authUser.id) {
                            this.showAuthed = true;
                        } else {
                            this.showHome = true;
                        }
                    }
                }


                console.log('onUserAuthed this.showHome: ',  this.showHome,  this.showAuthed,  this.showAdmin);

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
