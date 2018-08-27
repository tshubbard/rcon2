<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark justify-content-between">
        <a class="navbar-brand" href="/">
            <img src="images/rr_wm.png" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#app-navbar-collapse" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <ul class="navbar-nav mr-auto">

            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav justify-content-end">
                <!-- Authentication Links -->
                <li class="nav-item active">
                    <a class="nav-link" href="/login">
                        Login
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/register">
                        Register
                    </a>
                </li>
            </ul>
        </div>
    </nav>

</template>
<script>
    export default {
        props: [
            'appNameData'
        ],
        data: function() {
            return {
                appName: '',
                errors: []
            }
        },
        created: function() {
            this.appName = _.clone(this.appNameData);
            console.log('[NavHome] this: HOME ', this);
        },
        methods: {
        },
        computed: {
        }
    }
</script>
