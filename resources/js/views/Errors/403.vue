<template>
    <error>
        <template #title>Недостаточно прав</template>
        <template #code>403</template>
    </error>
</template>

<script>
import Error from "./Error.vue";
export default {
    name: "403",
    components: {
        Error
    }
}
</script>

<style scoped>

</style>
