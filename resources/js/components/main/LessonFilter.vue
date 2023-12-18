<template>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel">
        <div class="offcanvas-header">
            <h3 id="offcanvasEndLabel" class="mb-0">Фильтры</h3>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <h4 class="lesson-filter-title">Группы</h4>
            <div class="lesson-filter-block">
                <div class="form-check form-check-big mb-1" v-for="(group, index) in filterData.groups">
                    <input class="form-check-input form-check-input-big" type="checkbox" :value="group.id"
                           :id="'group' + index"
                           v-model="markedFilters.groups"
                    >
                    <label class="form-check-label" :for="'group' + index">
                        {{ group.title }} ({{ group.lesson_count }})
                    </label>
                </div>
            </div>
            <h4 class="lesson-filter-title">Инструктора</h4>
            <div class="lesson-filter-block">
                <div class="form-check form-check-big mb-1" v-for="(teacher, index) in filterData.teachers">
                    <input class="form-check-input form-check-input-big" type="checkbox" :value="teacher.id"
                           :id="'teacher' + index"
                           v-model="markedFilters.teachers"
                    >
                    <label class="form-check-label" :for="'teacher' + index">
                        {{ teacher.last_name_initials }}
                        ({{ teacher.lesson_count }})
                    </label>
                </div>
            </div>
            <h4 class="lesson-filter-title">Аудитории</h4>
            <div class="lesson-filter-block">
                <div class="form-check form-check-big mb-1" v-for="(location, index) in filterData.locations">
                    <input class="form-check-input form-check-input-big" type="checkbox" :value="location.id"
                           :id="'location' + index"
                           v-model="markedFilters.locations"
                    >
                    <label class="form-check-label" :for="'location' + index">
                        {{ location.title }} ({{ location.lesson_count }})
                    </label>
                </div>
            </div>

            <a v-if="user.role_id !== 1" href="#" class="btn btn-primary w-100 mt-2" @click.prevent="showOnlyMy">Только мои занятия</a>
            <a href="#" class="btn btn-primary w-100 mt-2" @click.prevent="resetFilters">Сбросить все фильтры</a>
        </div>
    </div>

</template>

<script>

export default {
    name: "LessonFilter",
    inject: ['user'],
    props: {
        markedFilters: {
            type: Object,
            required: true
        },
        filterData: {
            type: Object,
            required: true
        }
    },
    emits: {
        setEmptyFilters() {
            return true
        },
        showOnlyMy() {
            return true
        }
    },
    methods: {
        showOnlyMy() {
            this.$emit('showOnlyMy')
        },
        resetFilters() {
            this.$emit('setEmptyFilters')
        }
    }

}
</script>

<style scoped>

</style>
