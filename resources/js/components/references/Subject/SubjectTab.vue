<template>
    <subject-form v-if="isShowModal"
                  :form-title="formTitle"
                  :form-action="formAction"
                  :element="element"
                  :page="page"
                  :errors="errors"
                  @store-element="storeElement"
                  @update-element="updateElement"
                  @delete-element="deleteElement"
                  @restore-element="restoreElement"
    />
    <div class="tab-pane fade show active" id="common2_data" role="tabpanel"
         aria-labelledby="common2-data-tab">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <h2>{{ titleRoute }} ({{ elements.meta?.total }})</h2>
                <div class="row">
                    <div class="col">
                        <button @click="this.showModalForm('Добавить', 'add');" class="btn btn-primary mb-2">Добавить
                        </button>
                    </div>
                </div>
                <div class="d-flex flex-wrap mb-2">
                    <div class="form-check-big me-2">
                        <select class="form-select" v-model="filters.trashed.value">
                            <option :value="null">Только активные</option>
                            <option value="only">Только скрытые</option>
                            <option value="with">Все</option>
                        </select>
                    </div>
                    <template v-for="(value, key, index) in filters">
                        <template v-if="value.value">
                            <filter-tab :keyStr="key" :title="value.title" :value="value.value"
                                        @set-empty-current-filter="setEmptyCurrentFilter"/>
                        </template>
                    </template>
                    <button class="btn btn-outline-primary" @click.prevent="setEmptyFilters" v-if="isFilters">Сбросить
                        фильтры
                    </button>
                </div>
                <div class="table-responsive table-responsive-reference">
                    <loader :show="isShowLoader"/>
                    <table class="table table-hover table-bordered table-sm componentTable bg-white">
                        <thead>
                        <tr>
                            <th class="num col-1">ID</th>
                            <th class="ref">Наименование</th>
                            <th class="ref">Описание</th>
                            <th style="width: 100px !important;" rowspan="2" class="text-center align-middle">
                                Управление
                            </th>
                        </tr>
                        <tr>
                            <td><input v-model.trim="filters.id.value" type="text" class="form-control"></td>
                            <td><input v-model.trim="filters.title.value" type="text" class="form-control"></td>
                            <td><input v-model.trim="filters.description.value" type="text" class="form-control"></td>
                        </tr>
                        </thead>
                        <tbody v-if="elements.data?.length > 0">
                        <tr v-for="element in elements.data">
                            <td>{{ element.id }}</td>
                            <td>{{ element.title }}</td>
                            <td>{{ element.description }}</td>
                            <td class="text-nowrap" v-if="!element.deleted_at">
                                <button
                                    @click="this.showModalForm('Редактировать', 'edit', element);"
                                    class="btn btn-primary m-2">Редактировать
                                </button>
                                <button
                                    @click="this.deleteElement(element.id, {page, ...filterParams})"
                                    class="btn btn-danger m-2">Скрыть
                                </button>
                            </td>
                            <td class="text-nowrap" v-else>
                                <button
                                    @click="this.restoreElement(element.id, {page, ...filterParams})"
                                    class="btn btn-success m-2">Восстановить
                                </button>
                            </td>
                        </tr>
                        </tbody>
                        <template v-else>
                            {{ isFilters || filters.trashed.value !== null ? 'Записей не найдено...' : 'Записей пока нет...' }}
                        </template>
                    </table>
                </div>

                <Pagination :data="elements" :limit="10" @pagination-change-page="getElementsWithPagination"/>
            </div>
        </div>
    </div>
</template>

<script>
import SubjectForm from "./SubjectForm";
import FilterTab from "../../buttons/FilterTab";
import Loader from '../../Loader.vue'
import LaravelVuePagination from 'laravel-vue-pagination';
import {Modal} from "bootstrap";

export default {
    name: "Subjects",
    inject: ['saveMsg', 'deleteMsg', 'restoreMsg', 'errorMsg'],
    components: {
        SubjectForm,
        FilterTab,
        Loader,
        Pagination: LaravelVuePagination
    },
    data() {
        return {
            errors: {},
            page: 1,
            elements: [],
            element: {
                id: null,
                title: '',
                description: ''
            },
            filters: {
                id: {
                    title: 'ID',
                    value: null
                },
                title: {
                    title: 'Наименование',
                    value: null
                },
                description: {
                    title: 'Описание',
                    value: null
                },
                trashed: {
                    title: '',
                    value: null
                }
            },
            isShowModal: false,
            isShowLoader: false,
            formTitle: '',
            formAction: '',
            modal: null,
            modalObj: null
        }
    },
    mounted() {
        this.getElements({page: 1})
    },
    computed: {
        titleRoute() {
            return this.$route.meta.title
        },
        isFilters() {
            return Object.values(this.filters).filter(filter => filter.value).length > 0
        },
        filterParams() {
            return Object.keys(this.filters)
                .filter(key => this.filters[key].value)
                .reduce((obj, key) => {
                    obj[key] = this.filters[key].value;
                    return obj;
                }, {});
        }
    },
    watch: {
        filters: {
            handler: function () {
                this.getElements({page: 1, ...this.filterParams})
            },
            deep: true
        }
    },
    methods: {
        async getElements(params) {
            this.isShowLoader = true
            await axios.get('/api/v1/references/subjects', {params})
                .then(res => {
                        this.elements = res.data
                    }
                )
                .catch(e => {
                    console.log(e);
                })
            this.isShowLoader = false
        },
        async storeElement() {
            await axios.post('/api/v1/references/subjects', this.element)
                .then(res => {
                    this.modalObj.hide()
                    this.getElements({page: this.page, ...this.filterParams})
                    this.$notify({title: this.saveMsg, type: 'success'})
                })
                .catch(e => {
                    if (e.response?.status === 422) {
                        this.errors = e.response.data.errors
                    }
                    this.$notify({title: this.errorMsg, type: 'danger'})
                })
        },
        async updateElement() {
            await axios.patch(`/api/v1/references/subjects/${this.element.id}`, this.element)
                .then(res => {
                    this.modalObj.hide()
                    this.getElements({page: this.page, ...this.filterParams})
                    this.$notify({title: this.saveMsg, type: 'success'})
                })
                .catch(e => {
                    if (e.response?.status === 422) {
                        this.errors = e.response.data.errors
                    }
                    this.$notify({title: this.errorMsg, type: 'danger'})
                })
        },
        async deleteElement(id) {
            await axios.delete(`/api/v1/references/subjects/${id}`)
                .then(res => {
                    if (this.elements.data.length === 1 && this.page !== 1) this.page--
                    this.getElements({page: this.page, ...this.filterParams})
                    this.$notify({title: this.deleteMsg, type: 'success'})
                })
                .catch(e => {
                    console.log(e);
                    this.$notify({title: this.errorMsg, type: 'danger'})
                })
        },
        async restoreElement(id) {
            await axios.get(`/api/v1/references/subjects/restore/${id}`)
                .then(res => {
                    this.getElements({page: this.page, ...this.filterParams})
                    this.$notify({title: this.restoreMsg, type: 'success'})
                })
                .catch(e => {
                    console.log(e);
                    this.$notify({title: this.errorMsg, type: 'danger'})
                })
        },
        async showModalForm(title, action, element = null) {
            if (element !== null) this.element = Object.assign({}, element)
            this.isShowModal = true
            await this.$nextTick()
            this.modal = document.getElementById('subjectForm')
            this.modalObj = new Modal(this.modal)
            this.formTitle = title + ' предмет'
            this.formAction = action
            this.modal.addEventListener("hidden.bs.modal", () => {
                this.element = {
                    id: null,
                    title: '',
                    description: ''
                }
                this.errors = {}
                this.isShowModal = false
            })
            this.modalObj.show()
        },
        getElementsWithPagination(page = 1) {
            this.page = page
            this.getElements({page: this.page, ...this.filterParams})
        },
        setEmptyFilters() {
            this.filters = {
                id: {
                    title: 'ID',
                    value: null
                },
                title: {
                    title: 'Наименование',
                    value: null
                },
                description: {
                    title: 'Описание',
                    value: null
                },
                trashed: {
                    title: '',
                    value: null
                }
            }
        },
        setEmptyCurrentFilter(key) {
            this.filters[key].value = null
        }
    }
}
</script>

<style scoped>

</style>
