<template>
    <loader :show="isShowLoader"/>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="visits" role="tabpanel" aria-labelledby="home-tab">
            <div v-if="groups.length > 0" class="table-responsive">
                <table class="table table-hover table-bordered table-sm componentTable bg-white">
                    <tbody>
                    <template v-for="group in groups">
                        <tr>
                            <td><h3>{{ group.title }}</h3></td>
                            <td colspan="2">
                                <div class="form-check form-check-big">
                                    <input @click="visitGroup(group)"
                                           v-model="group.visitCheck"
                                           class="form-check-input form-check-input-big" type="checkbox"
                                           id="flexCheckChecked"
                                           title="добавить группе отметку о посещении">
                                    Проставить для всей группы
                                </div>
                            </td>
                        </tr>
                        <tr v-for="student in group.students">
                            <td>{{ student.full_name }}</td>
                            <td>
                                <div class="form-check form-check-big">
                                    <input
                                        v-model="student.visited"
                                        @change="visitHandle(group)"
                                        :true-value="1"
                                        :false-value="0"
                                        class="form-check-input form-check-input-big" type="checkbox"
                                        id="flexCheckChecked"
                                        title="добавить отметку о посещении">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input
                                        v-model="student.comment"
                                        type="text" class="form-control border-0" id="title"
                                        placeholder="комментарий"
                                        title="комментарий к посещению"
                                        maxlength="100"
                                    >
                                </div>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
            <template v-else>
                У занятия пока нет групп...
            </template>
        </div>
    </div>
    <div class="modal-footer">
        <button @click="visitsUpdate" type="button"
                class="btn btn-primary">
            Сохранить посещаемость
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
    </div>
</template>

<script>
import Loader from '../../Loader.vue'

export default {
    name: "LessonVisits",
    components: {
        Loader
    },
    props: {
        elementId: {
            type: String,
            required: true
        },
    },
    emits: {
        setNotification() {
            return true
        }
    },
    async mounted() {
        await this.getVisits()
    },
    data() {
        return {
            isShowLoader: false,
            visits: [],
            groups: []
        }
    },
    methods: {
        visitHandle(group) {
            if (group.students.filter(student => student.visited).length !== group.students.length) {
                group.visitCheck = false
            } else if (group.students.filter(student => student.visited).length === group.students.length) {
                group.visitCheck = true
            }
        },
        visitGroup(group) {
            group.visitCheck = !group.visitCheck
            group.students.forEach(student => {
                student.visited = group.visitCheck ? 1 : 0
            })
        },
        createStudentsVisits(groups, visits) {
            groups.forEach(group => {
                let groupObj = {
                    id: group.id,
                    title: group.title,
                    students: [],
                    visitCheck: true
                }
                group.students.forEach(student => {
                    let studentObj = {
                        id: student.id,
                        full_name: student.full_name
                    }
                    let filteredVisits = visits.filter(visit => visit.user_id === student.id && visit.group_id === group.id)
                    if (filteredVisits.length > 0) {
                        Object.assign(studentObj, ...filteredVisits)
                    } else {
                        Object.assign(studentObj,
                            {
                                lesson_id: this.elementId,
                                user_id: student.id,
                                group_id: group.id,
                                visited: 0,
                                comment: null
                            })
                    }
                    if (!studentObj.visited) groupObj.visitCheck = false
                    groupObj.students.push(studentObj)
                })
                this.groups.push(groupObj)
            })
        },
        async getVisits() {
            this.isShowLoader = true
            await axios.get(`/api/v1/lessons/${this.elementId}/visits`)
                .then(res => {
                    let groups = res.data.data.groups
                    let students_visits = []
                    res.data.data.students_visits.forEach(user => {
                        students_visits.push(user.pivot)
                    })
                    this.createStudentsVisits(groups, students_visits)
                })
            this.isShowLoader = false
        },
        visitsUpdate() {
            const students_visits_update = []
            this.groups.forEach(group => {
                let filteredVisits = group.students.filter(student => student.visited !== 0 || student.comment !== null)
                if (filteredVisits.length > 0) {
                    students_visits_update.push(...filteredVisits)
                }
            })
            axios.patch(`/api/v1/lessons/${this.elementId}/visits`, {students_visits: students_visits_update})
                .then(res => {
                    this.$emit('setNotification', 'success')
                })
                .catch(e => {
                    console.log(e);
                    this.$emit('setNotification', 'error')
                })
        }
    }
}
</script>

<style scoped>

</style>
