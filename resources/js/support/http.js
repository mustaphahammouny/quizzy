import axios from "axios";

const instance = axios.create({
    // baseURL: `${import.meta.env.VITE_APP_URL}`,
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
    },
});

const request = async ({method, url, data, headers}) => {
    const config = {
        method: method,
        url: url,
    };

    if (method === 'get') {
        config.params = data;
    } else {
        config.data = data;
    }

    try {
        return await instance.request(config);
    } catch (e) {
        console.log(e.message);

        return false;
    }
}

export default {
    get: (url, data = {}, headers = {}) => request({method: 'get', url, data, headers}),
    post: (url, data = {}, headers = {}) => request({method: 'post', url, data, headers}),
    put: (url, data = {}, headers = {}) => request({method: 'put', url, data, headers}),
    delete: (url, data = {}, headers = {}) => request({method: 'delete', url, data, headers}),
};