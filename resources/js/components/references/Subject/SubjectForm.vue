<template>
    <div class="modal fade" id="subjectForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">{{ formTitle }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Наименование:<span class="text-danger">*</span></label>
                        <input v-model.trim="element.title" type="text" :class="{'is-invalid':errors.title}" class="form-control" id="title" maxlength="100">
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
                </div>
                <div class="modal-footer">
                    <button v-if="formAction === 'add'"
                            @click="storeElement()"
                            type="button" class="btn btn-primary">Сохранить
                    </button>
                    <button v-else
                            @click="updateElement()"
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
    name: "SubjectForm",
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
    methods: {
        storeElement() {
            this.$emit('storeElement')
        },
        updateElement(id) {
            this.$emit('updateElement')
        }
    },
    watch: {
        'element.title'(newVal) {
            if (this.errors.title) delete this.errors.title
        }
    }
}
</script>

<style scoped>

</style>
