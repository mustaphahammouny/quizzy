import LayoutBackend from "@/layouts/variations/Backend.vue";

const Dashboard = () => import("@/views/student/DashboardView.vue");

const IndexQuiz = () => import("@/views/student/quizzes/IndexView.vue");
const FavoriteQuiz = () => import("@/views/student/quizzes/FavoriteView.vue");
const ShowQuiz = () => import("@/views/student/quizzes/ShowView.vue");

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
            },
            {
                path: "quizzes",
                children: [
                    {
                        path: "",
                        name: "student.quizzes.index",
                        component: IndexQuiz,
                    },
                    {
                        path: "favorite",
                        name: "student.quizzes.favorite",
                        component: FavoriteQuiz,
                    },
                    {
                        path: ":id(\\d+)/show",
                        name: "student.quizzes.show",
                        component: ShowQuiz,
                    },
                ],
            },
        ],
    },
];

export default routes;
