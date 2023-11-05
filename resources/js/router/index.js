import { createRouter, createWebHashHistory } from "vue-router";

import NProgress from "nprogress/nprogress.js";

// Main layouts
import LayoutSimple from "@/layouts/variations/Simple.vue";

import authRoutes from "./auth";
import studentRoutes from "./student";
import teacherRoutes from "./teacher";

import { useAuthStore } from "../stores/auth.store";

// Frontend: Home
const Home = () => import("@/views/HomeView.vue");

// Set all routes
const routes = [
    {
        path: "/",
        component: LayoutSimple,
        children: [
            {
                path: "",
                name: "home",
                component: Home,
            },
        ],
    },
    ...authRoutes,
    ...studentRoutes,
    ...teacherRoutes,
];

// Create Router
const router = createRouter({
    history: createWebHashHistory(),
    linkActiveClass: "active",
    linkExactActiveClass: "active",
    scrollBehavior() {
        return { left: 0, top: 0 };
    },
    routes,
});

// NProgress
/*eslint-disable no-unused-vars*/
NProgress.configure({ showSpinner: false });

router.beforeResolve((to, from, next) => {
    NProgress.start();

    const auth = useAuthStore();

    if (to.meta.auth && !auth.user) {
        next({name: 'auth.signin'});    
    } else if (to.meta.guest && auth.user) {
        next({name: `${auth.user.role}.dashboard`});
    } else {
        next();
    }
});

router.afterEach((to, from) => {
    NProgress.done();
});
/*eslint-enable no-unused-vars*/

export default router;
