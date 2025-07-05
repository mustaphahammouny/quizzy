const LayoutBackendBoxed = () => import("@/layouts/variations/BackendBoxed.vue");

const Dashboard = () => import("@/views/student/DashboardView.vue");

const EditProfile = () => import("@/views/profile/EditView.vue");

const IndexQuiz = () => import("@/views/student/quizzes/IndexView.vue");
const FavoriteQuiz = () => import("@/views/student/quizzes/FavoriteView.vue");
const PassedQuiz = () => import("@/views/student/quizzes/PassedView.vue");
const ShowQuiz = () => import("@/views/student/quizzes/ShowView.vue");
const StartQuiz = () => import("@/views/student/quizzes/StartView.vue");

const routes = [
    {
        path: "/student",
        redirect: "/student/dashboard",
        component: LayoutBackendBoxed,
        meta: { auth: true, role: "student" },
        children: [
            {
                path: "dashboard",
                name: "student.dashboard",
                component: Dashboard,
            },
            {
                path: "profile",
                name: "student.profile.edit",
                component: EditProfile,
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
                        path: "passed",
                        name: "student.quizzes.passed",
                        component: PassedQuiz,
                    },
                    {
                        path: ":id(\\d+)/show",
                        name: "student.quizzes.show",
                        component: ShowQuiz,
                    },
                    {
                        path: ":id(\\d+)/start",
                        name: "student.quizzes.start",
                        component: StartQuiz,
                    },
                ],
            },
        ],
    },
];

export default routes;
