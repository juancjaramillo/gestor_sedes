import axios, { AxiosError } from "axios";
import type {
  AxiosInstance,
  InternalAxiosRequestConfig,
  AxiosRequestHeaders,
} from "axios";

const api: AxiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  timeout: 10000,
});

// Request: agrega API Key de forma typesafe
api.interceptors.request.use((config: InternalAxiosRequestConfig) => {
  const apiKey = import.meta.env.VITE_API_KEY as string | undefined;

  // Aseguramos que headers siempre exista y sea del tipo correcto
  const headers: AxiosRequestHeaders = config.headers as AxiosRequestHeaders;
  if (apiKey) {
    headers["x-api-key"] = apiKey;
  }
  config.headers = headers;

  return config;
});

// Response: manejo centralizado de errores
api.interceptors.response.use(
  (res) => res,
  (err: AxiosError) => {
    const message =
      (err.response?.data as any)?.error?.message ??
      err.message ??
      "Network/Server error";
    return Promise.reject(new Error(message));
  },
);

export default api;
