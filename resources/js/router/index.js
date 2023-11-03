import { createRouter, createWebHashHistory } from "vue-router";

import NProgress from "nprogress/nprogress.js";

// Main layouts
import LayoutSimple from "@/layouts/variations/Simple.vue";

import authRoutes from "./auth";
import studentRoutes from "./student";
import teacherRoutes from "./teacher";

// Frontend: Landing
const Landing = () => import("@/views/LandingView.vue");

// Set all routes
const routes = [
    {
        path: "/",
        component: LayoutSimple,
        children: [
            {
                path: "",
                name: "landing",
                component: Landing,
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
    next();
});

router.afterEach((to, from) => {
    NProgress.done();
});
/*eslint-enable no-unused-vars*/

export default router;
