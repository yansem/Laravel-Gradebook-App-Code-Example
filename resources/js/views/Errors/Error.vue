<template>
    <main class="py-4">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="errorPage">
                                <div class="errorPage__topBlock">
                                    <div class="errorPage__image"></div>
                                    <div class="errorPage__title">
                                        <slot name="title"></slot>
                                    </div>
                                    <div class="errorPage__subtitle">
                                        <slot name="code"></slot>
                                    </div>
                                    <router-link :to="{name: 'main'}" class="btn btn-primary errorPage__btnMain">На главную</router-link>
                                </div>
                                <div class="errorPage__bottom-block">
                                    <div class="errorPage__message">
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    name: "Error"
}
</script>

<style scoped>

</style>
