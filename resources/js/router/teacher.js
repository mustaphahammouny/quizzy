import LayoutBackend from "@/layouts/variations/Backend.vue";

const TeacherDashboard = () => import("@/views/teacher/DashboardView.vue");

const routes = [
    {
        path: "/teacher",
        redirect: "/teacher/dashboard",
        component: LayoutBackend,
        children: [
            {
                path: "dashboard",
                name: "teacher.dashboard",
                component: TeacherDashboard,
            },
        ],
    },
];

export default routes;