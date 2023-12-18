<template>
    <div class="row mb-2">
        <div class="col-9">
            <h2>Мой профиль</h2>
        </div>
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">ФИО</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ user.full_name }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Логин</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ user.login }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Роль</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ user.role.title }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Admin",
    inject: ['user']
}
</script>

<style scoped>

</style>
