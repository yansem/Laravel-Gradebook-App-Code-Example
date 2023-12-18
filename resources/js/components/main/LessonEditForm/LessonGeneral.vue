<template>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="grades" role="tabpanel" aria-labelledby="profile-tab">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Дата и время начала:<span
                    class="text-danger">*</span></label>
                <input v-model="element.start" type="datetime-local" :class="{'is-invalid':errors.start}"
                       class="form-control"
                       id="exampleInputEmail1" name="exampleInputEmail1">
                <template v-if="errors.start">
                    <div class="invalid-feedback" v-for="error in errors.start">
                        {{ error }}
                    </div>
                </template>
            </div>
            <div class="mb-3">
                <label for="hours" class="form-label">Продолжительность (минуты):<span
                    class="text-danger">*</span></label>
                <input v-model="element.duration_minutes" type="number" max="240"
                       :class="{'is-invalid':errors.duration_minutes}" class="form-control" id="duration"
                       name="duration">
                <template v-if="errors.duration_minutes">
                    <div class="invalid-feedback" v-for="error in errors.duration_minutes">
                        {{ error }}
                    </div>
                </template>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Наименование:<span class="text-danger">*</span></label>
                <input v-model.trim="element.title" type="text" :class="{'is-invalid':errors.title}"
                       class="form-control"
                       id="title" maxlength="100">
                <template v-if="errors.title">
                    <div class="invalid-feedback" v-for="error in errors.title">
                        {{ error }}
                    </div>
                </template>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Описание:</label>
                <textarea v-model.trim="element.description" class="form-control"
                          :class="{'is-invalid':errors.description}"
                          id="description" rows="3"></textarea>
                <template v-if="errors.description">
                    <div class="invalid-feedback" v-for="error in errors.description">
                        {{ error }}
                    </div>
                </template>
            </div>
            <div class="mb-3">
                <label for="disabledSelect" class="form-label">Инструктор:<span class="text-danger">*</span></label>
                <select v-model="element.teacher_id" :class="{'is-invalid':errors.teacher_id}" class="form-select"
                        aria-label="Default select example">
                    <option :value="null" disabled selected>не указан</option>
                    <template v-for="teacher in teachers">
                        <option :value="teacher.id">{{ teacher.full_name }}</option>
                    </template>
                </select>
                <template v-if="errors.teacher_id">
                    <div class="invalid-feedback" v-for="error in errors.teacher_id">
                        {{ error }}
                    </div>
                </template>
            </div>
            <div class="mb-3">
                <label for="disabledSelect" class="form-label">Предмет:<span class="text-danger">*</span></label>
                <select v-model="element.subject_id" :class="{'is-invalid':errors.subject_id}" class="form-select"
                        aria-label="Default select example">
                    <option :value="null" disabled selected>не указан</option>
                    <template v-for="subject in subjects">
                        <option :value="subject.id">{{ subject.title }}</option>
                    </template>
                </select>
                <template v-if="errors.subject_id">
                    <div class="invalid-feedback" v-for="error in errors.subject_id">
                        {{ error }}
                    </div>
                </template>
            </div>
            <div class="mb-3">
                <label for="disabledSelect" class="form-label">Тип занятия:<span class="text-danger">*</span></label>
                <select v-model="element.work_id" :class="{'is-invalid':errors.work_id}" class="form-select"
                        aria-label="Default select example">
                    <option :value="null" disabled selected>не указан</option>
                    <template v-for="work in works">
                        <option :value="work.id">{{ work.title }}</option>
                    </template>
                </select>
                <template v-if="errors.work_id">
                    <div class="invalid-feedback" v-for="error in errors.work_id">
                        {{ error }}
                    </div>
                </template>
            </div>
            <div class="mb-3">
                <label for="disabledSelect" class="form-label">Аудитория:<span class="text-danger">*</span></label>
                <select v-model="element.location_id" :class="{'is-invalid':errors.location_id}" class="form-select"
                        aria-label="Default select example">
                    <option :value="null" disabled selected>не указана</option>
                    <template v-for="location in locations">
                        <option :value="location.id">{{ location.title }}</option>
                    </template>
                </select>
                <template v-if="errors.location_id">
                    <div class="invalid-feedback" v-for="error in errors.location_id">
                        {{ error }}
                    </div>
                </template>
            </div>
            <div class="mb-3">
                <label for="disabledSelect" class="form-label">Группы:</label>
                <div class="table-responsive" style="height: 300px">
                    <table
                        class="table table-hover table-bordered table-sm componentTable bg-white">
                        <tbody>
                        <tr v-for="group in groups">
                            <td>{{ group.title }}</td>
                            <td>
                                <div class="form-check form-check-big">
                                    <input v-model="element.groups_id"
                                           :value="group.id"
                                           class="form-check-input form-check-input-big" type="checkbox"
                                           id="flexCheckChecked">
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button @click="deleteElement" type="button"
                class="btn btn-danger" data-bs-dismiss="modal">
            Отменить занятие
        </button>
        <button @click="updateElement(element);" type="button"
                class="btn btn-primary">
            Сохранить общую информацию
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
    </div>


</template>

<script>
import moment from 'moment'

export default {
    name: "LessonGeneral",
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
        updateElement(obj) {
            return true
        },
        deleteElement() {
            return true
        },
        setNotification() {
            return true
        }
    },
    data() {
        return {
            element: {},
            subjects: [],
            locations: [],
            groups: [],
            teachers: [],
            works: []
        }
    },
    async mounted() {
        this.getElement()
        this.getReferences()
    },
    methods: {
        getElement() {
            axios.get(`/api/v1/lessons/${this.elementId}`)
                .then(res => {
                    const lesson = res.data.data
                    this.element = {
                        id: lesson.id,
                        start: moment(lesson.start).format('YYYY-MM-DD HH:mm'),
                        duration_minutes: lesson.duration_minutes,
                        title: lesson.title,
                        description: lesson.description,
                        teacher_id: lesson.teacher.deleted_at ? null : lesson.teacher.id,
                        subject_id: lesson.subject.deleted_at ? null : lesson.subject.id,
                        work_id: lesson.work.deleted_at ? null : lesson.work.id,
                        location_id: lesson.location.deleted_at ? null : lesson.location.id,
                        groups: lesson.groups,
                        groups_id: lesson.groups_id,
                        deleted_at: lesson.deleted_at
                    }
                })
                .catch(e => {
                    console.log(e);
                })
        },
        getReferences() {
            axios.get('/api/v1/references_general')
                .then(res => {
                    this.teachers = res.data.data.teachers
                    this.subjects = res.data.data.subjects
                    this.locations = res.data.data.locations
                    this.works = res.data.data.works
                    this.groups = res.data.data.groups
                })
                .catch(e => {
                    console.log(e);
                })
        },
        updateElement(element) {
            this.$emit('updateElement', {element: element})
        },
        deleteElement() {
            this.$emit('deleteElement')
        }
    },
    watch: {
        'element.start'(newVal) {
            if (this.element.start) {
                this.element.start = moment(this.element.start, 'yyyy-MM-DDTHH:mm').format(`yyyy-MM-DD HH:mm`)
            }
            if (this.errors.start) delete this.errors.start
        },
        'element.duration_minutes'(newVal) {
            if (this.errors.duration_minutes) delete this.errors.duration_minutes
        },
        'element.title'(newVal) {
            if (this.errors.title) delete this.errors.title
        },
        'element.description'(newVal) {
            if (this.errors.description) delete this.errors.description
        },
        'element.teacher_id'(newVal) {
            if (this.errors.teacher_id) delete this.errors.teacher_id
        },
        'element.subject_id'(newVal) {
            if (this.errors.subject_id) delete this.errors.subject_id
        },
        'element.work_id'(newVal) {
            if (this.errors.work_id) delete this.errors.work_id
        },
        'element.location_id'(newVal) {
            if (this.errors.location_id) delete this.errors.location_id
        }
    }
}
</script>

<style scoped>

</style>
