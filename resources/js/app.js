require('./bootstrap');

import axios from 'axios'
import {createApp} from 'vue'
import Index from './views/Index'
import Notifications from '@kyvg/vue3-notification'
import router from "./router/router";

const user = JSON.parse(localStorage.getItem('user'))
axios.interceptors.response.use(config => {
    return config
}, error => {
    if (error.response.status === 401) {
        location.href = '/login'
    }
    return Promise.reject(error);
})

let app = createApp({
    provide: {
        saveMsg: 'Сохранено',
        deleteMsg: 'Скрыто',
        restoreMsg: 'Восстановлено',
        errorMsg: 'Ошибка',
        user
    }
});

app.use(router)
app.use(Notifications)
app.component("index", Index);
app.mount("#app");
