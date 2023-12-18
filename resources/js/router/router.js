import {createRouter, createWebHistory} from "vue-router";

const user = JSON.parse(localStorage.getItem('user'))

const routes = [
    {
        path: '/', component: () => import('../components/main/Calendar'),
        name: 'main',
    },
    {
        path: '/profile',
        component: function () {
            if (user.role_id === 1) {
                return import('../views/Profile/Admin')
            } else if (user.role_id === 2) {
                return import('../views/Profile/Teacher')
            } else if (user.role_id === 3) {
                return import('../views/Profile/Student')
            }
        },
        name: 'profile',
    },
    {
        path: '/references', component: () => import('../views/References'),
        name: 'references',
        redirect: {name: 'references.groups'},
        meta: { onlyAdmin: true },
        children: [
            {
                path: 'groups', component: () => import('../components/references/Group/GroupTab'),
                name: 'references.groups',
                meta: { onlyAdmin: true, title: 'Группы' }
            },
            {
                path: 'subjects', component: () => import('../components/references/Subject/SubjectTab'),
                name: 'references.subjects',
                meta: { onlyAdmin: true, title: 'Предметы' }
            },
            {
                path: 'locations', component: () => import('../components/references/Location/LocationTab'),
                name: 'references.locations',
                meta: { onlyAdmin: true, title: 'Аудитории' }
            },
            {
                path: 'works', component: () => import('../components/references/Work/WorkTab'),
                name: 'references.works',
                meta: { onlyAdmin: true, title: 'Типы занятий' }
            },
            {
                path: 'grades', component: () => import('../components/references/Grade/GradeTab'),
                name: 'references.grades',
                meta: { onlyAdmin: true, title: 'Оценки' }
            },
        ]
    },
    {
        path: '/users', component: () => import('../views/Users'),
        name: 'users',
        meta: { onlyAdmin: true, title: 'Пользователи' }
    },
    {
        path: '/settings', component: () => import('../views/Settings'),
        name: 'settings',
        meta: { onlyAdmin: true }
    },
    {
        path: '/403', component: () => import('../views/Errors/403.vue'),
        name: '403',
    },
    {
        path: '/:pathMatch(.*)*', component: () => import('../views/Errors/404.vue'),
        name: '404'
    }
]

const router = createRouter({
    routes,
    history: createWebHistory(process.env.BASE_URL)
})

router.beforeEach((to, from, next) => {
    if (user.role_id === 2 || user.role_id === 3) {
        if (to.meta.onlyAdmin) {
            return next({name: '403'})
        }
    }
    next()
})

export default router;
