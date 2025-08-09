import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    "x-api-key": import.meta.env.VITE_API_KEY as string,
  },
});

api.interceptors.response.use(
  (r) => r,
  (error) => {
    const message =
      error?.response?.data?.error?.message ??
      error?.message ??
      "Network/Unknown error";
    return Promise.reject(new Error(message));
  }
);

export async function listLocations(params: {
  name?: string;
  code?: string;
  page?: number;
  per_page?: number;
}) {
  const res = await api.get("/v1/locations", { params });
  return res.data;
}

export async function createLocationFD(fd: FormData) {
  const res = await api.post("/v1/locations", fd); // multipart automático
  return res.data;
}

export async function updateLocationFD(id: number, fd: FormData) {
  const res = await api.post(`/v1/locations/${id}`, fd);
  return res.data;
}

export default api;
