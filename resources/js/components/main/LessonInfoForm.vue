<template>
    <div class="modal fade" id="lessonInfoForm" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Информация о занятии {{ element.deleted_at !== null ? ' (ОТМЕНЕНО)' : '' }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table
                            class="table table-hover table-bordered table-sm componentTable bg-white">
                            <tbody>
                            <tr>
                                <td>Дата и время начала:</td>
                                <td>{{ lessonStartFormatted }}</td>
                            </tr>
                            <tr>
                                <td>Продолжительность (минуты):</td>
                                <td>{{ element.duration_minutes }}</td>
                            </tr>
                            <tr>
                                <td>Наименование:</td>
                                <td class="modal-info-title">{{ element.title }}</td>
                            </tr>
                            <tr>
                                <td>Описание:</td>
                                <td class="modal-info-description">{{ element.description }}</td>
                            </tr>
                            <tr>
                                <td>Инструктор:</td>
                                <td>{{ element.teacherFullName }}</td>
                            </tr>
                            <tr>
                                <td>Предмет:</td>
                                <td>{{ element.subjectTitle }}</td>
                            </tr>
                            <tr>
                                <td>Тип занятия:</td>
                                <td>{{ element.workTitle }}</td>
                            </tr>
                            <tr>
                                <td>Аудитория:</td>
                                <td>{{ element.locationTitle }}</td>
                            </tr>
                            <tr>
                                <td>Группы:</td>
                                <td>{{ groupsFormatted }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        <button v-if="user.role_id !== 3"
                                @click="restoreElement();"
                                type="button"
                                class="btn btn-success" data-bs-dismiss="modal">
                            Восстановить занятие
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment/moment";

export default {
    name: "LessonInfoForm",
    inject: ['user'],
    props: {
        element: {
            type: Object,
            required: true
        }
    },
    emits: {
        restoreElement() {
            return true
        },
        setNotification() {
            return true
        }
    },
    computed: {
        lessonStartFormatted() {
            return moment(this.element.start).format('DD.MM.yyyy HH:mm')
        },
        groupsFormatted() {
            let groupsStr = ''
            this.element.groups.forEach(group => {
                groupsStr = groupsStr + group.title + ', '
            })
            groupsStr = groupsStr.slice(0, -2);
            return groupsStr
        }
    },
    methods: {
        restoreElement() {
            this.$emit('restoreElement')
        },
        setNotification(msg) {
            this.$emit('setNotification', msg)
        }
    }
}
</script>

<style scoped>

</style>
