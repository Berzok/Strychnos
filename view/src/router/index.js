import { createRouter, createWebHistory } from 'vue-router';
import { useStore as useUserStore } from './../store/modules/user';
import Home from '../views/Home.vue';
import Upload from "../components/upload/Upload";
export const LOGIN_PAGE_NAME = 'login';
export const HOME_PAGE_NAME = 'Home';
const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/about',
        name: 'About',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import(/* webpackChunkName: "about" */ './../views/About.vue'),
    },
    {
        path: '/profile',
        name: 'profile',
        component: () => import('./../components/user/UserDetail.vue'),
    },
    {
        path: '/upload',
        name: 'upload',
        component: () => import('./../components/upload/Upload.vue')
    },
    {
        path: '/images/',
        name: 'images',
        component: () => import('./../components/image/Images.vue'),
        children: [
            {
                path: '',
                alias: '/list',
                name: 'list',
                component: () => import('../components/image/List.vue'),
            },
            {
                path: 'view/:id',
                name: 'view',
                component: () => import('../components/image/ImageView.vue'),
            },
        ]
    },
    {
        path: '/authenticate',
        name: 'authenticate',
        meta: { requiresAuth: false },
        component: () => import('./../components/authenticate/Authenticate.vue'),
        children: [
            {
                path: 'login',
                name: LOGIN_PAGE_NAME,
                meta: { requiresAuth: false },
                component: () => import('./../components/authenticate/Login.vue'),
            },
            {
                path: 'register',
                name: 'register',
                meta: { requiresAuth: false },
                component: () => import('./../components/authenticate/Register.vue'),
            },
        ],
    },
];
const router = createRouter({
    history: createWebHistory(process.env.VUE_APP_BASE_URL),
    routes,
});
router.beforeEach((to, from, next) => {
    // by defining in by negation (to.meta.requiresAuth !== false) every page wich is not explicitly
    // defining to be without authentication needs to be authentication (security concerns)

    next();

    /*
    if (to.matched.some((record) => record.meta.requiresAuth !== false)) {
        if (useUserStore().getters.isLogged) {
            next();
        }
        else {
            next({ name: LOGIN_PAGE_NAME });
        }
    }
    else {
        next();
    }
     */
});
export default router;