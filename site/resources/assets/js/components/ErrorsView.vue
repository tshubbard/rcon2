<template>
    <div v-if="errors.length" class="error-wrapper">
        <div class="errors alert-danger">
            <b>Please correct the following error(s):</b>
            <ul>
                <li v-for="error in errors">{{ error }}</li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default {
        props: [
            'errors'
        ],
        data: function() {
            return {}
        },
        created: function() {
            console.log('this: ', this);
        },
        methods: {
        },
        computed: {
        }
    }
</script>
