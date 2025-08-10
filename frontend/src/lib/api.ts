import axios from "axios";
import type { Location, Paginated } from "@/types/location";

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
});

api.interceptors.request.use((config) => {
  config.headers = config.headers ?? {};
  (config.headers as Record<string, string>)["x-api-key"] =
    String(import.meta.env.VITE_API_KEY || "");
  return config;
});

api.interceptors.response.use(
  (res) => res,
  (error) => {
    const msg =
      error?.response?.data?.error?.message ||
      error?.response?.data?.message ||
      error.message ||
      "Error";
    return Promise.reject(new Error(msg));
  }
);

export async function listLocations(params: {
  page?: number; per_page?: number; name?: string; code?: string;
}): Promise<Paginated<Location>> {
  const res = await api.get<Paginated<Location>>("/v1/locations", { params });
  return res.data;
}

export async function createLocationFD(fd: FormData): Promise<Location> {
  const res = await api.post("/v1/locations", fd, {
    headers: { "Content-Type": "multipart/form-data" },
  });
  return res.data as Location;
}

// PUT con multipart en PHP: POST + _method=PUT
export async function updateLocationFD(id: number, fd: FormData): Promise<Location> {
  // FormData tiene .has() nativo: úsalo en vez de .entries() + .some()
  if (!fd.has("_method")) fd.append("_method", "PUT");

  const res = await api.post(`/v1/locations/${id}`, fd, {
    headers: { "Content-Type": "multipart/form-data" },
  });
  return res.data as Location;
}

export const createLocation = createLocationFD;
export default api;
