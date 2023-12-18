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
                <li class="nav-item" role="presentation" v-for="(group, index) in groups">
                    <button class="nav-link"
                            :class="{active: index === 0}"
                            :id="'tab_group_' + group.id" data-bs-toggle="tab"
                            :data-bs-target="'#group_' + group.id" type="button" role="tab"
                            :aria-controls="'group_' + group.id" aria-selected="true">
                        {{ group.title }}
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div v-for="(group, index) in groups"
                     class="tab-pane fade"
                     :class="{'active show': index === 0}"
                     :id="'group_' + group.id" role="tabpanel"
                     :aria-labelledby="'group_' + group.id"
                >
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <template v-if="group.subjects.length > 0">
                            <div class="card mb-3" v-for="subject in group.subjects">
                                <div class="card-body">
                                    <h2 class="h3">{{ subject.title }}</h2>
                                    <div class="table-responsive">
                                        <table
                                            class="table table-profile table-hover table-sm componentTable bg-white">
                                            <thead>
                                            <tr>
                                                <th>Дата</th>
                                                <th>Занятие</th>
                                                <th>Оценки</th>
                                                <th>Посещение</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="lesson in subject.lessons">
                                                <td width="25%">{{ lesson.start }}</td>
                                                <td width="25%">{{ lesson.title }}</td>
                                                <td width="25%">
                                                    <template v-if="lesson.grades.length > 0">
                                                        <div v-for="grade in lesson.grades">
                                                            {{ grade.grade_value }} {{ grade.work_title }}
                                                            {{ grade.comment }}
                                                        </div>
                                                    </template>
                                                    <template v-else>
                                                        -
                                                    </template>
                                                </td>
                                                <td width="25%">
                                                    <template v-if="Object.keys(lesson.visit).length > 0">
                                                        {{ lesson.visit.visited }} {{ lesson.visit.comment }}
                                                    </template>
                                                    <template v-else>
                                                        Н
                                                    </template>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div>Средняя оценка: {{ subject.gradeAvg }}</div>
                                        <div>Посещаемость: {{ subject.visitPercent }} %</div>
                                    </div>
                                </div>
                            </div>
                            </template>
                            <template v-else>
                                У группы пока нет занятий...
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
    name: "Student",
    inject: ['user'],
    data() {
        return {
            groups: []
        }
    },
    mounted() {
        this.getGroups()
    },
    methods: {
        getGroups() {
            axios.get('/api/v1/profile')
                .then(res => {
                    this.groups = res.data.data
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
