import LayoutBackend from "@/layouts/variations/Backend.vue";

const Dashboard = () => import("@/views/teacher/DashboardView.vue");
const Quizzes = () => import("@/views/teacher/QuizzesView.vue");

const routes = [
    {
        path: "/teacher",
        redirect: "/teacher/dashboard",
        component: LayoutBackend,
        meta: { auth: true, role: "teacher" },
        children: [
            {
                path: "dashboard",
                name: "teacher.dashboard",
                component: Dashboard,
            },
            {
                path: "quizzes",
                name: "teacher.quizzes",
                component: Quizzes,
            },
        ],
    },
];

export default routes;