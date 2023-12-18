<template>
    <div class="table-responsive" style="max-width: 50%;">
        <table
            class="table table-hover table-bordered table-sm componentTable bg-white">
            <tbody>
            <tr>
                <td width="50%">Время начала занятий (чч:мм):</td>
                <td width="50%">
                    <input type="time" class="form-control" v-model="settings.min_time" :class="{'is-invalid':errors.min_time}">
                    <div class="invalid-feedback" v-for="error in errors.min_time">
                        {{ error }}
                    </div>
                </td>
            </tr>
            <tr>
                <td width="50%">Время окончания занятий (чч:мм):</td>
                <td width="50%">
                    <input type="time" class="form-control" :value="maxTimeFormat" @input="event => settings.max_time = event.target.value"
                           :class="{'is-invalid':errors.max_time}">
                    <div class="invalid-feedback" v-for="error in errors.max_time">
                        {{ error }}
                    </div>
                </td>
            </tr>
            <tr>
                <td width="50%">Максимальная продолжительность занятия (минуты):</td>
                <td width="50%">
                    <input v-model="settings.lesson_max_duration" type="number"
                           :class="{'is-invalid':errors.lesson_max_duration}" class="form-control" id="duration"
                           name="duration">
                    <div class="invalid-feedback" v-for="error in errors.lesson_max_duration">
                        {{ error }}
                    </div>
                </td>
            </tr>
            <tr>
                <td width="50%">Частота временных интервалов (чч:мм):</td>
                <td width="50%">
                    <input type="time" class="form-control" v-model="settings.slot_duration" :class="{'is-invalid':errors.slot_duration}">
                    <div class="invalid-feedback" v-for="error in errors.slot_duration">
                        {{ error }}
                    </div>
                </td>
            </tr>
            <tr>
                <td width="50%">Частота отображения временных интервалов (чч:мм):</td>
                <td width="50%">
                    <input type="time" class="form-control" v-model="settings.slot_label_duration" :class="{'is-invalid':errors.slot_label_duration}">
                    <div class="invalid-feedback" v-for="error in errors.slot_label_duration">
                        {{ error }}
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <button @click="update" type="button" class="btn btn-primary">
        Сохранить
    </button>
    <notifications :duration="1000" width="200">
        <template #body="props">
            <div ref="alert" class="alert text-center" :class="'alert-' + props.item.type" role="alert">
                {{ props.item.title }}
            </div>
        </template>
    </notifications>
</template>

<script>
export default {
    name: "Settings",
    inject: ['saveMsg', 'errorMsg'],
    data() {
        return {
            settings: {
                min_time: '',
                max_time: '',
                lesson_max_duration: '',
                slot_duration: '',
                slot_label_duration: ''
            },
            errors: ''
        }
    },
    computed: {
        settingsFormat() {
            return {
                min_time: this.settings.min_time,
                max_time: this.settings.max_time === '00:00' ? '24:00' : this.settings.max_time,
                lesson_max_duration: this.settings.lesson_max_duration,
                slot_duration: this.settings.slot_duration,
                slot_label_duration: this.settings.slot_label_duration
            }
        },
        maxTimeFormat() {
            return this.settings.max_time === '24:00' ? '00:00' : this.settings.max_time
        }
    },
    mounted() {
        this.getSettings()
    },
    watch: {
        'settings.min_time'(newVal) {
            if (this.errors.min_time) delete this.errors.min_time
            if (this.errors.max_time) delete this.errors.max_time
        },
        'settings.max_time'(newVal) {
            if (this.errors.min_time) delete this.errors.min_time
            if (this.errors.max_time) delete this.errors.max_time
        },
        'settings.lesson_max_duration'(newVal) {
            if (this.errors.lesson_max_duration) delete this.errors.lesson_max_duration
        },
        'settings.slot_duration'(newVal) {
            if (this.errors.slot_duration) delete this.errors.slot_duration
        },
        'settings.slot_label_duration'(newVal) {
            if (this.errors.slot_label_duration) delete this.errors.slot_label_duration
        },
    },
    methods: {
        async getSettings() {
            await axios.get('/api/v1/settings')
                .then(res => {
                    this.settings.min_time = res.data.data.min_time
                    this.settings.max_time = res.data.data.max_time
                    this.settings.lesson_max_duration = res.data.data.lesson_max_duration
                    this.settings.slot_duration = res.data.data.slot_duration
                    this.settings.slot_label_duration = res.data.data.slot_label_duration
                })
                .catch(e => {
                    console.log(e);
                })
        },
        async update() {
            await axios.patch('/api/v1/settings', this.settingsFormat)
                .then(res => {
                    this.$notify({title: this.saveMsg, type: 'success'})
                })
                .catch(e => {
                    console.log(e);
                    if (e.response?.status === 422) {
                        this.errors = e.response.data.errors
                    }
                    this.$notify({title: this.errorMsg, type: 'danger'})
                })
        }
    }
}
</script>

<style scoped>

</style>
