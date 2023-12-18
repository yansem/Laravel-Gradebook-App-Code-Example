<template>
    <div class="bg-grey_light me-2 d-flex align-items-center" v-if="keyStr !== 'trashed'">
        {{ title + ':' + value }}
        <svg @click.prevent="$emit('setEmptyCurrentFilter', keyStr)" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x filter-tab-close" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path></svg>
    </div>
</template>

<script>
export default {
    name: "FilterTab",
    props: {
        keyStr: {
            type: String,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        value: {
            type: String,
            required: true
        }
    },
    emits: {
        setEmptyCurrentFilter() {
            return true
        }
    }
}
</script>

<style scoped>

</style>
