import { defineStore } from "pinia";
import alert from "@/support/alert";

export const useNotificationStore = defineStore({
    id: "notification",
    state: () => ({
        message: {
            icon: null,
            title: null,
        },
    }),
    actions: {
        setMessage(message) {
            this.message = message;
        },
        clearMessage() {
            this.message = {
                icon: null,
                title: null,
            };
        },
        show() {
            if (!this.message.title) {
                return;
            }

            alert.show(this.message);

            this.clearMessage();
        },
    },
});
