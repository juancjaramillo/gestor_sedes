import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
});

api.interceptors.request.use((config) => {
  config.headers = config.headers ?? {};
  (config.headers as any)["x-api-key"] = import.meta.env.VITE_API_KEY;
  return config;
});

api.interceptors.response.use(
  (res) => res,
  (error) => {
    const msg = error?.response?.data?.error?.message || error.message || "Network error";
    return Promise.reject(new Error(msg));
  }
);

export default api;
