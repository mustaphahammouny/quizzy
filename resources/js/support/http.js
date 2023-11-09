import axios from "axios";

import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth.store";

const instance = axios.create({
    // baseURL: `${import.meta.env.SPA_URL}`,
    withCredentials: true,
    headers: {
        "Content-Type": "application/json",
    },
});

const router = useRouter();

const request = async ({ method, url, data, headers }) => {
    const config = {
        method: method,
        url: url,
    };

    if (method === "get") {
        config.params = data;
    } else {
        config.data = data;
    }

    try {
        return await instance.request(config);
    } catch (e) {
        const auth = useAuthStore();

        if (e.response.status === 401) {
            await auth.logout();
            router.push({ name: "auth.signin" });
        } else {
            throw new Error(e.response.data.message);
        }
    }
};

export default {
    get: (url, data = {}, headers = {}) =>
        request({ method: "get", url, data, headers }),
    post: (url, data = {}, headers = {}) =>
        request({ method: "post", url, data, headers }),
    put: (url, data = {}, headers = {}) =>
        request({ method: "put", url, data, headers }),
    delete: (url, data = {}, headers = {}) =>
        request({ method: "delete", url, data, headers }),
};
