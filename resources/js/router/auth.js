import LayoutSimple from "@/layouts/variations/Simple.vue";

const AuthSignIn = () => import("@/views/auth/SignInView.vue");
const AuthSignUp = () => import("@/views/auth/SignUpView.vue");
const AuthReminder = () => import("@/views/auth/ReminderView.vue");

const routes = [
    {
        path: "/auth",
        component: LayoutSimple,
        children: [
            {
                path: "signin",
                name: "auth.signin",
                component: AuthSignIn,
                meta: { guest: true },
            },
            {
                path: "signup",
                name: "auth.signup",
                component: AuthSignUp,
                meta: { guest: true },
            },
            {
                path: "reminder",
                name: "auth.reminder",
                component: AuthReminder,
                meta: { guest: true },
            },
        ],
    },
];

export default routes;
