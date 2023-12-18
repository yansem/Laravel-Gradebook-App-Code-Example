<template>
    <loader :show="isShowLoader"/>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="visits" role="tabpanel" aria-labelledby="home-tab">
            <div v-if="groups.length > 0" class="table-responsive">
                <table class="table table-hover table-bordered table-sm componentTable bg-white">
                    <tbody>
                    <template v-for="group in groups">
                        <tr>
                            <td colspan="2">
                                <h3 class="mt-3">{{ group.title }}</h3>
                            </td>
                        </tr>
                        <tr v-for="student in group.students">
                            <td>{{ student.full_name }}</td>
                            <td>
                                <div class="grade" v-for="(grade, index) in student.grades" :key="index">
                                    <i @click="removeGrade(group.id, student.id, index)" class="bi bi-x grade-remove grade-icon" title="удалить оценку"></i>
                                    <div class="grade-fields">
                                        <div class="flex-grow-1">
                                            <select v-model="grade.work_id" @input="workHandler(grade, $event)" class="form-select" aria-label="Default select example">
                                                <option value="">выбрать тип работы</option>
                                                <option v-for="work in works" :value="work.id">{{ work.title }}</option>
                                            </select>
                                        </div>
                                        <div class="flex-grow-1">
                                            <select v-model="grade.grade_id" class="form-select" aria-label="Default select example" :disabled="!grade.work_id">
                                                <option :value="null">выбрать оценку</option>
                                                <option v-for="grade in grades" :value="grade.id">{{ grade.title }}</option>
                                            </select>
                                        </div>
                                        <div class="flex-grow-1">
                                            <input v-model="grade.comment" type="text" class="form-control" id="title" placeholder="комментарий"
                                                   title="комментарий к оценке"
                                                   :disabled="!grade.grade_id"
                                                   maxlength="100" disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-add">
                                    <i role="button" @click="addGrade(group.id, student.id)" class="bi bi-plus grade-icon" title="добавить оценку"></i>
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
        <button @click="gradesUpdate" type="button"
                class="btn btn-primary">
            Сохранить оценки
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
    </div>
</template>

<script>
import Loader from '../../Loader.vue'

export default {
    name: "LessonGrades",
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
    components: {
        Loader
    },
    async mounted() {
        await this.getReferences()
        await this.getGrades()
    },
    data() {
        return {
            isShowLoader: false,
            errors: null,
            groups: [],
            grades: [],
            works: [],
            work_id: null
        }
    },
    methods: {
        getReferences() {
            axios.get('/api/v1/references_grades')
                .then(res => {
                    this.works = res.data.data.works
                    this.grades = res.data.data.grades
                })
                .catch(e => {
                    console.log(e);
                })
        },
        addGrade(group_id, student_id) {
            this.groups
                .find(group => group.id === group_id).students
                .find(student => student.id === student_id).grades
                .push(
                    {
                        lesson_id: this.elementId,
                        user_id: student_id,
                        group_id: group_id,
                        work_id: '',
                        grade_id: null,
                        comment: ''
                    }
                )
        },
        removeGrade(group_id, student_id, index) {
            this.groups
                .find(group => group.id === group_id).students
                .find(student => student.id === student_id).grades
                .splice(index, 1)
        },
        createStudentsGrades(groups, grades) {
            groups.forEach(group => {
                let groupObj = {
                    id: group.id,
                    title: group.title,
                    students: []
                }
                group.students.forEach(student => {
                    let studentObj = {
                        id: student.id,
                        full_name: student.full_name,
                        grades: []
                    }
                    let filteredGrades = grades.filter(grade => grade.user_id === student.id && grade.group_id === group.id)
                    if (filteredGrades.length > 0) {
                        studentObj.grades = filteredGrades
                    } else {
                        studentObj.grades.push(
                            {
                                lesson_id: this.elementId,
                                user_id: student.id,
                                group_id: group.id,
                                work_id: this.work_id,
                                grade_id: null,
                                comment: ''
                            })
                    }
                    groupObj.students.push(studentObj)
                })
                this.groups.push(groupObj)
            })
        },
        async getGrades() {
            this.isShowLoader = true
            await axios.get(`/api/v1/lessons/${this.elementId}/grades`)
                .then(res => {
                    this.work_id = res.data.data.work_id
                    let groups = res.data.data.groups
                    let students_grades = []
                    res.data.data.students_grades.forEach(user => {
                        students_grades.push(user.pivot)
                    })
                    this.createStudentsGrades(groups, students_grades)
                })
            this.isShowLoader = false
        },
        gradesUpdate() {
            const students_grades_update = []
            this.groups.forEach(group => {
                group.students.forEach(student => {
                    let filteredGrades = student.grades.filter(student => student.grade_id !== null)
                    if (filteredGrades.length > 0) {
                        students_grades_update.push(...filteredGrades)
                    }
                })
            })
            axios.patch(`/api/v1/lessons/${this.elementId}/grades`, {students_grades: students_grades_update})
                .then(res => {
                    this.$emit('setNotification', 'success')
                })
                .catch(e => {
                    console.log(e);
                    this.$emit('setNotification', 'error')
                })
        },
        workHandler(grade, event) {
            if (!event.target.value) {
                grade.grade_id = null
                grade.comment = ''
            }
        }

    }
}
</script>

<style scoped>

</style>
