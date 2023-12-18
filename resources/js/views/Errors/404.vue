<template>
    <error>
        <template #title>Страница не найдена</template>
        <template #code>404</template>
    </error>
</template>

<script>
import Error from "./Error.vue";
export default {
    name: "404",
    components: {
        Error
    }
}
</script>

<style scoped>

</style>
