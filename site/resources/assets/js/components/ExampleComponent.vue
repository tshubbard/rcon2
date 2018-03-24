<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                        <input v-model="message">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        msg: 'hello',
        data: function() {
            return {
                message: this.msg
            }
        },
        methods: {
            mounted() {
                console.log('Component mounted.')

            }
        }
    }
</script>
