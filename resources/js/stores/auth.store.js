import { defineStore } from "pinia";

import http from "@/support/http";

export const useAuthStore = defineStore({
    id: "auth",
    state: () => ({
        user: JSON.parse(localStorage.getItem("user")) ?? null,
        returnUrl: null,
    }),
    actions: {
        setUser(user) {
            this.user = user;

            if (user) {
                localStorage.setItem("user", JSON.stringify(this.user));
            } else {
                localStorage.removeItem("user");
            }
        },
        async attempt() {
            try {
                const response = await http.get("api/user");

                this.setUser(response.data.data);
            } catch (e) {
                let message = "Something went wrong!";

                throw new Error(e.response.data.message ?? message);
            }
        },
        async login(credentials) {
            try {
                await http.post("login", credentials);

                await this.attempt();
            } catch (e) {
                let message = "Something went wrong!";

                throw new Error(e.response.data.message ?? message);
            }
        },
        async logout() {
            await http.post("logout");

            this.setUser(null);
        },
    },
});
