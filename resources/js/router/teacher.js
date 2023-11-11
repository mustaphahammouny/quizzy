import LayoutBackend from "@/layouts/variations/Backend.vue";

const Dashboard = () => import("@/views/teacher/DashboardView.vue");

const IndexQuiz = () => import("@/views/teacher/quizzes/IndexView.vue");
const CreateQuiz = () => import("@/views/teacher/quizzes/CreateView.vue");
const EditQuiz = () => import("@/views/teacher/quizzes/EditView.vue");

const CreateQuestion = () => import("@/views/teacher/questions/CreateView.vue");
const EditQuestion = () => import("@/views/teacher/questions/EditView.vue");

const CreateAnswer = () => import("@/views/teacher/answers/CreateView.vue");
const EditAnswer = () => import("@/views/teacher/answers/EditView.vue");

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
                        name: "teacher.quizzes.index",
                        component: IndexQuiz,
                    },
                    {
                        path: "create",
                        name: "teacher.quizzes.create",
                        component: CreateQuiz,
                    },
                    {
                        path: ":id/edit",
                        name: "teacher.quizzes.edit",
                        component: EditQuiz,
                    },
                    {
                        path: ":quizId/questions/create",
                        name: "teacher.questions.create",
                        component: CreateQuestion,
                    },
                    {
                        path: ":quizId/questions/:id/edit",
                        name: "teacher.questions.edit",
                        component: EditQuestion,
                    },
                ],
            },
            // {
            //     path: "quizzes/:id/questions/create",
            //     name: "teacher.questions.create",
            //     component: CreateQuestion,
            // },
            // {
            //     path: "questions/edit/:id",
            //     name: "teacher.questions.edit",
            //     component: EditQuestion,
            // },
            {
                path: "questions/:id/answers/create",
                name: "teacher.answers.create",
                component: CreateAnswer,
            },
            {
                path: "answers/edit/:id",
                name: "teacher.answers.edit",
                component: EditAnswer,
            },
        ],
    },
];

export default routes;
