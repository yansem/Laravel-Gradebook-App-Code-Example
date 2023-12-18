<template>
    <div class="modal fade" id="groupForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">{{ formTitle }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Наименование:<span class="text-danger">*</span></label>
                        <input v-model.trim="element.title" type="text" :class="{'is-invalid':errors.title}" class="form-control" id="title" required maxlength="100">
                        <template v-if="errors.title">
                            <div class="invalid-feedback" v-for="error in errors.title">
                                {{ error }}
                            </div>
                        </template>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание:</label>
                        <textarea v-model.trim="element.description" class="form-control" :class="{'is-invalid':errors.description}" id="description" rows="3"></textarea>
                        <template v-if="errors.description">
                            <div class="invalid-feedback" v-for="error in errors.description">
                                {{ error }}
                            </div>
                        </template>
                    </div>
                    <div class="mb-3">
                        <label for="date_start" class="form-label">Дата начала:<span class="text-danger">*</span></label>
                        <input v-model="element.date_start" type="date" :class="{'is-invalid':errors.date_start}" class="form-control" id="date_start" required>
                        <template v-if="errors.date_start">
                            <div class="invalid-feedback" v-for="error in errors.date_start">
                                {{ error }}
                            </div>
                        </template>
                    </div>
                    <div class="mb-3">
                        <label for="date_end" class="form-label">Дата окончания:<span class="text-danger">*</span></label>
                        <input v-model="element.date_end" type="date" :class="{'is-invalid':errors.date_end}" class="form-control" id="date_end" required>
                        <template v-if="errors.date_end">
                            <div class="invalid-feedback" v-for="error in errors.date_end">
                                {{ error }}
                            </div>
                        </template>
                    </div>
                    <div class="mb-3">
                        <label for="disabledSelect" class="form-label">Студенты:</label>
                        <div class="table-responsive table-responsive-form-group">
                            <table
                                class="table table-hover table-bordered table-sm componentTable bg-white">
                                <tbody>
                                <template v-if="students.data?.length > 0">
                                    <tr v-for="student in students.data">
                                        <td>{{ student.full_name }}</td>
                                        <td>
                                            <div class="form-check form-check-big">
                                                <input v-model="element.students_id"
                                                       :value="student.id"
                                                       class="form-check-input form-check-input-big" type="checkbox"
                                                       id="flexCheckChecked">
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-else>
                                    <td>Студентов пока нет...</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button v-if="formAction === 'add'"
                            @click="storeElement"
                            type="button" class="btn btn-primary">Сохранить
                    </button>
                    <button v-else
                            @click="updateElement"
                            type="button" class="btn btn-primary">Сохранить
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "GroupForm",
    props: {
        element: {
            type: Object,
            required: true
        },
        page: {
            type: Number,
            required: true
        },
        formTitle: {
            type: String,
            required: true
        },
        formAction: {
            type: String,
            required: true
        },
        errors: {
            type: Object,
            required: true
        }
    },
    emits: {
        storeElement() {
            return true
        },
        updateElement() {
            return true
        }
    },
    data() {
        return {
            students: []
        }
    },
    async mounted() {
        await this.getStudents()
    },
    methods: {
        async getStudents() {
            await axios.get('/api/v1/students')
                .then(res => {
                    this.students = res.data
                })
                .catch(e => {
                    console.log(e);
                })
        },
        storeElement() {
            this.$emit('storeElement')
        },
        updateElement() {
            this.$emit('updateElement')
        }
    },
    watch: {
        'element.title'(newVal) {
            if (this.errors.title) delete this.errors.title
        },
        'element.date_start'(newVal) {
            if (this.errors.date_start) delete this.errors.date_start
        },
        'element.date_end'(newVal) {
            if (this.errors.date_end) delete this.errors.date_end
        }
    }
}
</script>

<style scoped>

</style>
