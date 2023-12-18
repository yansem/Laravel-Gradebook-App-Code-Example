<template>
    <div class="modal fade" id="userForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
         data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">{{ formTitle }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Фамилия:<span class="text-danger">*</span></label>
                        <input v-model.trim="element.last_name" type="text" :class="{'is-invalid':errors.last_name}"
                               class="form-control" id="last_name" maxlength="255">
                        <template v-if="errors.last_name">
                            <div class="invalid-feedback" v-for="error in errors.last_name">
                                {{ error }}
                            </div>
                        </template>
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Имя:<span class="text-danger">*</span></label>
                        <input v-model.trim="element.first_name" type="text" :class="{'is-invalid':errors.first_name}"
                               class="form-control" id="first_name" maxlength="255">
                        <template v-if="errors.first_name">
                            <div class="invalid-feedback" v-for="error in errors.first_name">
                                {{ error }}
                            </div>
                        </template>
                    </div>
                    <div class="mb-3">
                        <label for="patronymic" class="form-label">Отчество:<span class="text-danger">*</span></label>
                        <input v-model.trim="element.patronymic" type="text" :class="{'is-invalid':errors.patronymic}"
                               class="form-control" id="patronymic" maxlength="255">
                        <template v-if="errors.patronymic">
                            <div class="invalid-feedback" v-for="error in errors.patronymic">
                                {{ error }}
                            </div>
                        </template>
                    </div>
                    <div class="mb-3">
                        <label for="login" class="form-label">Логин:<span class="text-danger">*</span></label>
                        <input v-model.trim="element.login" type="text" :class="{'is-invalid':errors.login}"
                               class="form-control" id="login" maxlength="255">
                        <template v-if="errors.login">
                            <div class="invalid-feedback" v-for="error in errors.login">
                                {{ error }}
                            </div>
                        </template>
                    </div>
                    <div class="mb-3">
                        <label for="disabledSelect" class="form-label">Роль:<span class="text-danger">*</span></label>
                        <select v-model="element.role_id" :class="{'is-invalid':errors.role_id}" class="form-select"
                                aria-label="Default select example">
                            <option :value="null" disabled selected>не указана</option>
                            <template v-for="role in roles.data">
                                <option :value="role.id">{{ role.title }}</option>
                            </template>
                        </select>
                        <template v-if="errors.role_id">
                            <div class="invalid-feedback" v-for="error in errors.role_id">
                                {{ error }}
                            </div>
                        </template>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль:<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input v-model.trim="element.password" type="text" :class="{'is-invalid':errors.password}"
                                   class="form-control" id="password" maxlength="255">
                            <button
                                @click="generatePass()"
                                type="button" class="btn btn-primary">Сгенерировать
                            </button>
                            <template v-if="errors.password">
                                <div class="invalid-feedback" v-for="error in errors.password">
                                    {{ error }}
                                </div>
                            </template>
                        </div>
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
    name: "UserForm",
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
            roles: [],
        }
    },
    mounted() {
        this.getRoles()
    },
    methods: {
        async getRoles() {
            await axios.get('/api/v1/references/roles')
                .then(res => {
                    this.roles = res.data
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
        },
        generatePass() {
            let chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            let passwordLength = 10;
            let password = "";
            for (let i = 0; i <= passwordLength; i++) {
                let randomNumber = Math.floor(Math.random() * chars.length);
                password += chars.substring(randomNumber, randomNumber + 1);
            }
            this.element.password = password
        },
        translit(word) {
            let result = '';
            const converter = {
                'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd',
                'е': 'e', 'ё': 'e', 'ж': 'zh', 'з': 'z', 'и': 'i',
                'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
                'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't',
                'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c', 'ч': 'ch',
                'ш': 'sh', 'щ': 'sch', 'ь': '', 'ы': 'y', 'ъ': '',
                'э': 'e', 'ю': 'yu', 'я': 'ya'
            };
            for (let i = 0; i < word?.length; ++i) {
                if (converter[word[i]] === undefined) {
                    result += word[i];
                } else {
                    result += converter[word[i]];
                }
            }
            return result;
        },
        loginCreate() {
            return this.translit(this.element.last_name.toLowerCase()) + '_' +
                this.translit(this.element.first_name.toLowerCase()[0]) +
                this.translit(this.element.patronymic.toLowerCase()[0])
        }
    },
    watch: {
        'element.last_name'(newVal) {
            this.element.login = this.loginCreate()
            if (this.errors.last_name) delete this.errors.last_name
        },
        'element.first_name'(newVal) {
            this.element.login = this.loginCreate()
            if (this.errors.first_name) delete this.errors.first_name
        },
        'element.patronymic'(newVal) {
            this.element.login = this.loginCreate()
            if (this.errors.patronymic) delete this.errors.patronymic
        },
        'element.login'(newVal) {
            if (newVal === '_') this.element.login = ''
            if (this.errors.login) delete this.errors.login
        },
        'element.role_id'(newVal) {
            if (this.errors.role_id) delete this.errors.role_id
        },
        'element.password'(newVal) {
            if (this.errors.password) delete this.errors.password
        }
    }
}
</script>

<style scoped>

</style>
