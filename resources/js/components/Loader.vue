<template>
    <div v-if="show" class="overlay">
        <div class="d-flex justify-content-center">
            <div class="spinner-grow text-primary" role="status" style="width: 3rem; height: 3rem; z-index: 20;">
                <span class="sr-only"></span>
            </div>
        </div>
    </div>
</template>

<style>

</style>

<script>
    export default {
       props: {
           show: { type: Boolean, required: true }
       }
    }
</script>
