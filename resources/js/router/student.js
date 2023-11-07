import LayoutBackend from "@/layouts/variations/Backend.vue";

const Dashboard = () => import("@/views/student/DashboardView.vue");
const Quizzes = () => import("@/views/student/QuizzesView.vue");

const routes = [
    {
        path: "/student",
        redirect: "/student/dashboard",
        component: LayoutBackend,
        meta: { auth: true, role: "student" },
        children: [
            {
                path: "dashboard",
                name: "student.dashboard",
                component: Dashboard,
                meta: { auth: true },
            },
            {
                path: "quizzes",
                name: "student.quizzes",
                component: Quizzes,
                meta: { auth: true },
            },
        ],
    },
];

export default routes;
