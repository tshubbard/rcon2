<template>
    <div v-cloak>
        <div id="index-carousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#index-carousel" data-slide-to="0" class="active"></li>
                <li data-target="#index-carousel" data-slide-to="1"></li>
                <li data-target="#index-carousel" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/rust-rcon2.jpg"
                         alt="First slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3>Real-Time Server Logs</h3>
                        <p>See your server chat and connection logs in real-time anywhere you have an internet connection!</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/rust-rcon2.jpg"
                         alt="Second slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3>Administrate YOUR Server</h3>
                        <p>Take action as events are unfolding on your server while you're nowhere near your PC!</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/rust-rcon2.jpg"
                         alt="Third slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3>Custom Events</h3>
                        <p>Keep your server "Vanilla" but easily set up custom event scripts when things happen on your server!</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#index-carousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#index-carousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</template>
<script>
    export default {
        created: function() {
            console.log('this: ', this);
        },
        methods: {
        },
        computed: {
        }
    }
</script>
