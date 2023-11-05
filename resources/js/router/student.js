import LayoutBackend from "@/layouts/variations/Backend.vue";

const StudentDashboard = () => import("@/views/student/DashboardView.vue");

const routes = [
    {
        path: "/student",
        redirect: "/student/dashboard",
        component: LayoutBackend,
        children: [
            {
                path: "dashboard",
                name: "student.dashboard",
                component: StudentDashboard,
                meta: { auth: true },
            },
        ],
    },
];

export default routes;