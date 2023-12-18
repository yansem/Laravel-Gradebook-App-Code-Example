<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <router-link class="nav-link" active-class="active" to="/" exact>Главная</router-link>
                    </li>
                    <template v-if="user.role_id === 1">
                        <li class="nav-item">
                            <router-link class="nav-link" active-class="active" :to="{name: 'references'}">Справочники
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" active-class="active" :to="{name: 'users'}">Пользователи
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" active-class="active" :to="{name: 'settings'}">Настройки
                            </router-link>
                        </li>
                    </template>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            {{ user.full_name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                            <router-link class="dropdown-item" :to="{name: 'profile'}">Профиль</router-link>
                            <a class="dropdown-item" href="/logout">Выйти</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="py-4">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <router-view/>
                </div>
            </div>
        </div>
    </main>
</template>

<script>

export default {
    name: "Index",
    inject: ['user']
}
</script>

<style scoped>

</style>
