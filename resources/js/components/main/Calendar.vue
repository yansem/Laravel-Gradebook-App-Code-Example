<template>
    <div class="row">
        <loader :show="isShowLoader"/>
        <div class="col-12">
            <lesson-add-form
                v-if="modal.type === 'add'"
                :calendar-api="calendarApi"
                :event-info="eventInfo"
                :errors="errors"
                @store-element="storeElement"
            />
            <lesson-edit-form
                v-if="modal.type === 'edit'"
                :calendar-api="calendarApi"
                :event-info="eventInfo"
                :elementId="elementId"
                :errors="errors"
                @update-element="updateElement"
                @delete-element="deleteElement"
                @set-notification="setNotification"
            />
            <lesson-info-form
                v-if="modal.type === 'info'"
                :element="element"
                @restore-element="restoreElement"
                @set-notification="setNotification"
            />
            <button class="btn btn-primary calendar-filters" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasEnd"
                    aria-controls="offcanvasEnd"><i class="bi bi-funnel"></i>
            </button>
            <div class="table-responsive">
                <full-calendar ref="fullCalendar" :options="calendarOptions"/>
            </div>

        </div>
        <lesson-filter
            :markedFilters="markedFilters"
            :filterData="filterData"
            @show-only-my="showOnlyMy"
            @set-empty-filters="setEmptyFilters"
        />
        <notifications :duration="1000">
            <template #body="props">
                <div class="alert text-center" :class="'alert-' + props.item.type" role="alert">
                    {{ props.item.title }}
                </div>
            </template>
        </notifications>
    </div>

</template>

<script>
import LessonInfoForm from "./LessonInfoForm.vue";
import LessonAddForm from "./LessonAddForm.vue";
import LessonEditForm from "./LessonEditForm/LessonEditForm.vue";
import LessonFilter from "./LessonFilter";
import Loader from '../Loader.vue'
import '@fullcalendar/core/vdom'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import timeGridPlugin from '@fullcalendar/timegrid';
import ruLocale from '@fullcalendar/core/locales/ru';
import moment from 'moment'
import {Modal} from "bootstrap";

export default {
    name: "Calendar",
    inject: ['saveMsg', 'deleteMsg', 'restoreMsg', 'errorMsg', 'user'],
    components: {
        LessonFilter,
        FullCalendar,
        LessonInfoForm,
        LessonAddForm,
        LessonEditForm,
        Loader
    },
    async mounted() {
        await this.getFilters()
        await this.getSettings()
    },
    data() {
        return {
            notificationMessage: '',
            settings: {},
            errors: {},
            loader: {
                getLessons: false,
                getSettings: false,
                getFilters: false
            },
            modal: {
                el: null,
                obj: null,
                type: ''
            },
            elements: [],
            eventInfo: null,
            elementId: null,
            element: {
                start: null,
                duration: '00:00',
                location_id: null,
                teacher_id: null,
                subject_id: null,
                groups_id: [],
                title: '',
                description: '',
                work_id: null,
                deleted_at: null
            },
            filterData: {
                groups: [],
                teachers: [],
                locations: []
            },
            markedFilters: {
                groups: this.user.role_id === 3 ? this.user.group_ids : [],
                teachers: this.user.role_id === 2 ? [this.user.id] : [],
                locations: [],
                lessons_start: '',
                lessons_end: ''
            },
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin],
                initialView: 'timeGridWeek',
                locale: ruLocale,
                height: 'auto',
                allDaySlot: false,
                expandRows: true,
                navLinks: true,
                slotEventOverlap: false,
                forceEventDuration: true,
                editable: (this.user.role_id !== 3),
                eventDisplay: 'block',
                eventMaxStack: 1,
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    omitZeroMinute: false,
                    meridiem: false
                },
                headerToolbar: {
                    start: 'title',
                    center: 'dayGridMonth timeGridWeek timeGridDay',
                    end: 'today prev,next',
                },
                events: [],
                eventContent: this.eventContentFunc,
                eventDidMount: function (info) {
                    // if (info.event.extendedProps.deleted_at) info.el.children[0].classList.add('crossed');
                },
                eventDataTransform: function (info) {
                    if (info.deleted_at) {
                        info.classNames = "crossed";
                        info.editable = false
                    }
                    return info;
                },
                eventClick: this.showEditModalForm,
                dateClick: this.showAddModalForm,
                eventDrop: this.eventDrop,
                eventResize: this.eventResize,
                datesSet: this.dateChange
            }
        }
    },
    computed: {
        isShowLoader() {
            return Object.values(this.loader).filter(Boolean).length > 0
        },
        calendarApi() {
            return this.$refs.fullCalendar.getApi()
        }
    },
    watch: {
        markedFilters: {
            handler: async function () {
                await this.getElements({stayCurDate: true}, this.markedFilters)
                await this.getLessonCount()
            },
            deep: true
        }
    },
    methods: {
        dateChange(dateInfo) {
            const start = moment(dateInfo.startStr).format('YYYY-MM-DD')
            const end = moment(dateInfo.endStr).format('YYYY-MM-DD')
            if (this.markedFilters.lessons_start !== start && this.markedFilters.lessons_end !== end) {
                this.markedFilters.lessons_start = start
                this.markedFilters.lessons_end = end
            }
        },
        async getElements(args, params) {
            this.loader.getElement = true
            await axios.get('/api/v1/lessons', {params})
                .then(res => {
                    this.elements = res.data.data
                    this.calendarOptions.events = res.data.data
                })
            this.loader.getElement = false
        },
        async getFilters() {
            this.loader.getFilters = true
            await axios.get('/api/v1/lesson_filters')
                .then(res => {
                        this.filterData.groups = res.data.data.groups
                        this.filterData.teachers = res.data.data.teachers
                        this.filterData.locations = res.data.data.locations
                    }
                )
                .catch(e => {
                    console.log(e);
                })
            this.loader.getFilters = false
        },
        async getSettings() {
            this.loader.getSettings = true
            await axios.get('/api/v1/settings')
                .then(res => {
                    this.settings = res.data.data
                    this.calendarApi.setOption('slotMinTime', this.settings.min_time + ':00')
                    this.calendarApi.setOption('slotMaxTime', this.settings.max_time + ':00')
                    this.calendarApi.setOption('slotDuration', this.settings.slot_duration + ':00')
                    this.calendarApi.setOption('slotLabelInterval', this.settings.slot_label_duration + ':00')
                    this.calendarApi.destroy()
                    this.calendarApi.render()
                })
                .catch(e => {
                    console.log(e);
                })
            this.loader.getSettings = false
        },
        async showAddModalForm(e) {
            if (this.user.role_id !== 3) {
                this.errors = {}
                this.eventInfo = e
                this.modal.type = 'add'
                await this.$nextTick()
                this.modal.el = document.getElementById('lessonAddForm')
                this.modal.el.addEventListener("hidden.bs.modal", () => {
                    this.elementId = null
                    this.modal.type = ''
                })
                this.modal.obj = new Modal(this.modal.el)
                this.modal.obj.show()
            }
        },
        async showEditModalForm(e) {
            document.querySelector('html').addEventListener('click', () => {
                let popover = document.querySelector('.fc-popover')
                if (popover) popover.remove()
            })
            this.errors = {}
            this.getFieldsFromEvent(e.event)
            this.elementId = e.event.id
            if (this.user.role_id !== 3 && this.element.deleted_at === null) {
                this.modal.type = 'edit'
                await this.$nextTick()
                this.modal.el = document.getElementById('lessonEditForm')
            } else {
                this.modal.type = 'info'
                await this.$nextTick()
                this.modal.el = document.getElementById('lessonInfoForm')
            }
            this.modal.obj = new Modal( this.modal.el)
            this.modal.el.addEventListener("hidden.bs.modal", () => {
                this.elementId = null
                this.modal.type = ''
            })
            this.modal.obj.show()
        },
        getFieldsFromEvent(event) {
            let lesson = _.cloneDeep(event)
            this.element = {
                id: lesson.id,
                start: moment(lesson.start).format('YYYY-MM-DD HH:mm'),
                duration_minutes: lesson.extendedProps.duration_minutes,
                title: lesson.title,
                description: lesson.extendedProps.description,
            }
            if (this.user.role_id === 3 || lesson.extendedProps.deleted_at !== null) {
                this.element.locationTitle = lesson.extendedProps.location.title
                this.element.teacherFullName = lesson.extendedProps.teacher.full_name
                this.element.subjectTitle = lesson.extendedProps.subject.title
                this.element.workTitle = lesson.extendedProps.work.title
                this.element.groups = lesson.extendedProps.groups
                this.element.deleted_at = lesson.extendedProps.deleted_at
            } else {
                this.element.teacher_id = lesson.extendedProps.teacher.deleted_at ? null : lesson.extendedProps.teacher.id
                this.element.subject_id = lesson.extendedProps.subject.deleted_at ? null : lesson.extendedProps.subject.id
                this.element.work_id = lesson.extendedProps.work.deleted_at ? null : lesson.extendedProps.work.id
                this.element.location_id = lesson.extendedProps.location.deleted_at ? null : lesson.extendedProps.location.id
                this.element.groups = lesson.extendedProps.groups
                this.element.groups_id = lesson.extendedProps.groups_id
                this.element.deleted_at = lesson.extendedProps.deleted_at
            }
        },
        eventContentFunc(info) {
            return {
                html:
                    info.event.title +
                    '<br>' + info.event.extendedProps.subject.title +
                    '<br>' + info.event.extendedProps.teacher.last_name_initials +
                    '<br>' + info.event.extendedProps.location.title +
                    '<br>' + moment(info.event.start).format('HH:mm') + '-' + moment(info.event.end).format('HH:mm')
            }
        },
        eventDrop(info) {
            const minTime = this.settings.min_time + ':00'
            const maxTime = this.settings.max_time + ':00'
            const newStart = moment(info.event.start).format('HH:mm:ss')
            const newEnd = moment(info.event.end).format('HH:mm:ss')
            let title
            if (moment(info.event.end).format('HH:mm') !== '00:00') {
                if (info.event.start.getDay() !== info.event.end.getDay()) {
                    title = 'Занятие должно быть в течение одного дня'
                } else if (newStart < minTime) {
                    title = 'Время начала занятия не может быть меньше ' + this.settings.min_time
                } else if (newEnd > maxTime) {
                    title = 'Время окончания занятия не может быть больше ' + this.settings.max_time
                }
            }
            if (title) {
                info.revert()
                this.$notify({title: title, type: 'danger'})
                return false
            }
            this.getFieldsFromEvent(info.event)
            this.updateElement({info, element: this.element})
        },
        eventResize(info) {
            const deltaMinutes = info.endDelta.days * 1440 + info.endDelta.milliseconds / 1000 / 60
            if (deltaMinutes > this.settings.lesson_max_duration) {
                info.revert()
                this.$notify({
                    title: 'Продолжительность не может быть больше ' + this.settings.lesson_max_duration + ' мин.',
                    type: 'danger'
                })
                return false
            }
            info.event.setExtendedProp('duration_minutes', info.event.extendedProps.duration_minutes + deltaMinutes)
            this.getFieldsFromEvent(info.event)
            this.updateElement({info, element: this.element})
        },
        storeElement(element) {
            axios.post('/api/v1/lessons', element)
                .then(async res => {
                    this.modal.obj.hide()
                    await this.getElements({stayCurDate: true}, this.markedFilters)
                    await this.getFilters()
                    await this.getLessonCount()
                    this.errors = {}
                    this.$notify({title: this.saveMsg, type: 'success'})
                })
                .catch(e => {
                    if (e.response?.status === 422) {
                        this.errors = e.response.data.errors
                    }
                    this.$notify({title: this.errorMsg, type: 'danger'})
                })
        },
        updateElement(obj) {
            axios.patch(`/api/v1/lessons/${obj.element.id}`, obj.element)
                .then(async res => {
                    await this.getElements({stayCurDate: true}, this.markedFilters)
                    await this.getFilters()
                    await this.getLessonCount()
                    this.errors = {}
                    this.$notify({title: this.saveMsg, type: 'success'})
                })
                .catch(e => {
                    if (e.response?.status === 422) {
                        this.errors = e.response.data.errors
                        this.$notify({title: 'Проверьте правильность заполнения полей занятия', type: 'danger'})
                    } else {
                        this.$notify({title: this.errorMsg, type: 'danger'})
                    }
                    obj.info?.revert()
                })
        },
        deleteElement() {
            axios.delete(`/api/v1/lessons/${this.elementId}`)
                .then(async res => {
                    await this.getElements({stayCurDate: true}, this.markedFilters)
                    await this.getFilters()
                    await this.getLessonCount()
                    this.$notify({title: 'Занятие отменено', type: 'success'})
                })
                .catch(e => {
                    console.log(e);
                    this.$notify({title: this.errorMsg, type: 'danger'})
                })
        },
        restoreElement() {
            axios.get(`/api/v1/lessons/restore/${this.elementId}`)
                .then(async res => {
                    await this.getElements({stayCurDate: true}, this.markedFilters)
                    await this.getFilters()
                    await this.getLessonCount()
                    this.$notify({title: this.restoreMsg, type: 'success'})
                })
                .catch(e => {
                    console.log(e);
                    this.$notify({title: this.errorMsg, type: 'danger'})
                })
        },
        setNotification(type) {
            if (Object.keys(this.errors).length === 0) {
                if (type === 'success') {
                    this.$notify({title: this.saveMsg, type: 'success'})
                } else if (type === 'error') {
                    this.$notify({title: this.errorMsg, type: 'danger'})
                }
            }
        },
        showOnlyMy() {
            this.markedFilters.groups = this.user.role_id === 3 ? this.user.group_ids : []
            this.markedFilters.teachers = this.user.role_id === 2 ? [this.user.id] : []
            this.markedFilters.locations = []
        },
        setEmptyFilters() {
            this.markedFilters.groups = []
            this.markedFilters.teachers = []
            this.markedFilters.locations = []
        },
        getLessonCount() {
            this.filterData.groups.forEach(group => {
                group.lesson_count = this.elements.filter(el => el.groups_id.includes(group.id)).length
            })
            this.filterData.teachers.forEach(teacher => {
                teacher.lesson_count = this.elements.filter(el => el.teacher.id === teacher.id).length
            })
            this.filterData.locations.forEach(location => {
                location.lesson_count = this.elements.filter(el => el.location.id === location.id).length
            })
        },
    }
}
</script>

<style>

.fc .fc-button {
    border-radius: 0;
}

.fc-timegrid-event, .fc-daygrid-event {
    cursor: pointer;
    border-radius: 0;
}

</style>
