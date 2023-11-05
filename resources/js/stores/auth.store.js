import { defineStore } from "pinia";

export const useAuthStore = defineStore({
    id: "auth",
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')),
        returnUrl: null,
    }),
    actions: {},
});