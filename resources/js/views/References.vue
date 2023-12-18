<template>
    <div>
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <router-link class="nav-link" active-class="active"
                             :to="{name: 'references.groups'}">Группы
                </router-link>
            </li>
            <li class="nav-item" role="presentation">
                <router-link class="nav-link" active-class="active" :to="{name: 'references.subjects'}">
                    Предметы
                </router-link>
            </li>
            <li class="nav-item" role="presentation">
                <router-link class="nav-link" active-class="active" :to="{name: 'references.locations'}">
                    Аудитории
                </router-link>
            </li>
            <li class="nav-item" role="presentation">
                <router-link class="nav-link" active-class="active" :to="{name: 'references.grades'}">
                    Оценки
                </router-link>
            </li>
            <li class="nav-item" role="presentation">
                <router-link class="nav-link" active-class="active" :to="{name: 'references.works'}">Типы
                    занятий
                </router-link>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <router-view/>
        </div>
    </div>
    <notifications :duration="1000" width="200">
        <template #body="props">
            <div ref="alert" class="alert text-center" :class="'alert-' + props.item.type" role="alert">
                {{ props.item.title }}
            </div>
        </template>
    </notifications>

</template>

<script>
export default {
    name: "References"
}
</script>

<style scoped>

</style>
