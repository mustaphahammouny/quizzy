import { defineStore } from "pinia";

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
            } catch (error) {
                let message = "Something went wrong!";

                throw new Error(error.response.data.message ?? message);
            }
        },
        async login(credentials) {
            try {
                await http.post("login", credentials);

                await this.attempt();
            } catch (error) {
                let message = "Something went wrong!";

                throw new Error(error.response.data.message ?? message);
            }
        },
        async logout() {
            await http.post("logout");

            this.setUser(null);
        },
    },
});
