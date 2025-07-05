import axios from "axios";
import router from "../router";

import { useAuthStore } from "@/stores/auth.store";

const instance = axios.create({
    // baseURL: `${import.meta.env.SPA_URL}`,
    withCredentials: true,
});

const request = async ({ method, url, data, headers }) => {
    const auth = useAuthStore();

    const config = {
        method: method,
        url: url,
        headers: {
            ...headers,
            "Content-Type": headers["Content-Type"] ?? "application/json",
        },
    };

    if (method === "get") {
        config.params = data;
    } else {
        config.data = data;
    }

    try {
        return await instance.request(config);
    } catch (error) {
        if (error.response.status === 401) {
            auth.setUser(null);

            router.push({ name: "auth.signin" });
        }

        throw error;
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
