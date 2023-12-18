<template>
    <div class="modal fade" id="lessonEditForm" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Редактировать занятие</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button @click="current = 'LessonGeneral'"
                                    :class="{active: current === 'LessonGeneral'}" class="nav-link" id="general-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#general"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Общая
                                информация
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button @click="current = 'LessonVisits'"
                                    :class="{active: current === 'LessonVisits'}" class="nav-link" id="visit-tab"
                                    data-bs-toggle="tab" data-bs-target="#visits"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Посещаемость
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button @click="current = 'LessonGrades'"
                                    :class="{active: current === 'LessonGrades'}" class="nav-link" id="grade-tab"
                                    data-bs-toggle="tab" data-bs-target="#grades"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false">Оценки
                            </button>
                        </li>
                    </ul>
                    <LessonGeneral
                        v-if="current === 'LessonGeneral'"
                        :calendar-api="calendarApi"
                        :event-info="eventInfo"
                        :elementId="elementId"
                        :errors="errors"
                        @update-element="updateElement"
                        @delete-element="deleteElement"
                        @set-notification="setNotification"
                    />
                    <LessonVisits
                        v-if="current === 'LessonVisits'"
                        :elementId="elementId"
                        @set-notification="setNotification"
                    />
                    <LessonGrades
                        v-if="current === 'LessonGrades'"
                        :elementId="elementId"
                        @set-notification="setNotification"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LessonGeneral from "./LessonGeneral.vue";
import LessonVisits from "./LessonVisits.vue";
import LessonGrades from "./LessonGrades.vue";


export default {
    name: "LessonEditForm",
    inject: ['user'],
    components: {
        LessonGeneral,
        LessonVisits,
        LessonGrades
    },
    props: {
        calendarApi: {
            type: Object,
            required: false
        },
        elementId: {
            type: String,
            required: false
        },
        eventInfo: {
            type: Object,
            required: false
        },
        errors: {
            type: Object,
            required: true
        }
    },
    emits: {
        updateElement(element) {
            return true
        },
        deleteElement(id) {
            return true
        },
        setNotification(type) {
            return true
        }
    },
    data() {
        return {
            current: 'LessonGeneral'
        }
    },
    methods: {
        updateElement(obj) {
            this.$emit('updateElement', obj)
        },
        deleteElement() {
            this.$emit('deleteElement')
        },
        setNotification(type) {
            this.$emit('setNotification', type)
        }
    }


}
</script>

<style scoped>

</style>
