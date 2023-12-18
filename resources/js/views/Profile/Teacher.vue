<template>
    <div class="row mb-2">
        <div class="col-9">
            <h2>Мой профиль</h2>
        </div>
        <div class="col-3 text-end">
            <a href="/profile/export" class="text-decoration-none me-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                     class="bi bi-download" viewBox="0 0 16 16">
                    <path
                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"></path>
                    <path
                        d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"></path>
                </svg>
                Скачать xlsx
            </a>
        </div>
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
        <div>
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation" v-for="(subject, index) in subjects">
                    <button class="nav-link"
                            :class="{active: index === 0}"
                            :id="'tab_subject_' + subject.id" data-bs-toggle="tab"
                            :data-bs-target="'#subject_' + subject.id" type="button" role="tab"
                            :aria-controls="'subject_' + subject.id" aria-selected="true">
                        {{ subject.title }}
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div v-for="(subject, index) in subjects"
                     class="tab-pane fade"
                     :class="{'active show': index === 0}"
                     :id="'subject_' + subject.id" role="tabpanel"
                     :aria-labelledby="'subject_' + subject.id">
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <template v-if="subject.groups.length > 0">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <template v-for="group in subject.groups">
                                            <h2 class="h3 mt-3">{{ group.title }}</h2>
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-profile table-hover table-sm componentTable bg-white">
                                                    <thead>
                                                    <tr>
                                                        <th>Дата</th>
                                                        <th>Занятие</th>
                                                        <th>Студент</th>
                                                        <th>Оценки</th>
                                                        <th>Посещение</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <template v-for="lesson in group.lessons">
                                                        <template v-if="lesson.students.length > 0">
                                                            <tr v-for="student in lesson.students">
                                                                <td width="20%">{{ lesson.start }}</td>
                                                                <td width="20%">{{ lesson.title }}</td>
                                                                <td width="20%">
                                                                    {{
                                                                        [student.last_name, student.first_name, student.patronymic].join(' ')
                                                                    }}
                                                                </td>
                                                                <td width="20%">
                                                                    <template v-if="student.grades.length > 0">
                                                                        <div v-for="grade in student.grades">
                                                                            {{ grade.grade_value }} {{
                                                                                grade.work_title
                                                                            }}
                                                                            {{ grade.comment }}
                                                                        </div>
                                                                    </template>
                                                                    <template v-else>
                                                                        -
                                                                    </template>
                                                                </td>
                                                                <td width="20%">
                                                                    <template
                                                                        v-if="Object.keys(student.visit).length > 0">
                                                                        {{ student.visit.visited }} {{
                                                                            student.visit.comment
                                                                        }}
                                                                    </template>
                                                                    <template v-else>
                                                                        Н
                                                                    </template>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                        <template v-else>
                                                            <tr>
                                                                <td>{{ lesson.start }}</td>
                                                                <td>{{ lesson.title }}</td>
                                                                <td>У группы пока нет студентов...</td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </template>
                                                    </template>
                                                    </tbody>
                                                </table>
                                                <div>Средняя оценка: {{ group.gradeAvg }}</div>
                                                <div>Посещаемость: {{ group.visitPercent }} %</div>
                                                <hr/>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                У предмета пока нет групп...
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Teacher",
    inject: ['user'],
    data() {
        return {
            subjects: []
        }
    },
    mounted() {
        this.getSubject()
    },
    methods: {
        getSubject() {
            axios.get('/api/v1/profile')
                .then(res => {
                    this.subjects = res.data.data
                })
                .catch(e => {
                    console.log(e);
                })
        }
    }
}
</script>

<style scoped>

</style>
