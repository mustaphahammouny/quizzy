import LayoutBackend from "@/layouts/variations/Backend.vue";

const Dashboard = () => import("@/views/teacher/DashboardView.vue");
const Quizzes = () => import("@/views/teacher/QuizzesView.vue");
const Quiz = () => import("@/views/teacher/QuizView.vue");

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
                children: [
                    {
                        path: "",
                        name: "teacher.quizzes",
                        component: Quizzes,
                    },
                    {
                        path: "create",
                        name: "teacher.quizzes.create",
                        component: Quiz,
                    },
                    {
                        path: "edit",
                        name: "teacher.quizzes.edit",
                        component: Quiz,
                    },
                ],
            },
        ],
    },
];

export default routes;
