import axios from "axios";
import type { Location, Paginated } from "@/types/location";


export const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api",
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
    const data = error?.response?.data;
    const msg =
      data?.error?.message ||
      data?.message ||
      error.message ||
      "Error";

    const e = new Error(msg) as Error & {
      status?: number;
      data?: unknown;
    };
    e.status = error?.response?.status;
    e.data = data;
    return Promise.reject(e);
  }
);

/** ---- Helpers ---- */
function makeFormData(
  payload: Record<string, unknown>,
  fileFields: Array<keyof typeof payload> = []
) {
  const fd = new FormData();
  Object.entries(payload).forEach(([k, v]) => {
    if (v === undefined || v === null) return;
     if (fileFields.includes(k as never) && v instanceof File) {
      fd.append(k, v);
    } else if (!(v instanceof File)) {
      fd.append(k, String(v));
    }
  });
  return fd;
}

/** ---- API: Locations ---- */
export async function listLocations(params: {
  page?: number;
  per_page?: number;
  name?: string;
  code?: string;
}): Promise<Paginated<Location>> {
  const res = await api.get<Paginated<Location>>("/v1/locations", { params });
  return res.data;
}

export async function getLocation(id: number): Promise<Location> {
  const res = await api.get<Location>(`/v1/locations/${id}`);
  return res.data;
}


export async function createLocationFD(fd: FormData): Promise<Location> {
  const res = await api.post("/v1/locations", fd, {
    headers: { "Content-Type": "multipart/form-data" },
  });
  return res.data as Location;
}


export async function createLocation(payload: {
  code: string;
  name: string;
  image?: File | null;
}): Promise<Location> {
  const fd = makeFormData(payload, ["image"]);
  const res = await api.post("/v1/locations", fd, {
    headers: { "Content-Type": "multipart/form-data" },
  });
  return res.data as Location;
}


export async function updateLocationFD(id: number, fd: FormData): Promise<Location> {
  if (!fd.has("_method")) fd.append("_method", "PUT");
  const res = await api.post(`/v1/locations/${id}`, fd, {
    headers: { "Content-Type": "multipart/form-data" },
  });
  return res.data as Location;
}


export async function updateLocation(
  id: number,
  payload: { code: string; name: string; image?: File | null }
): Promise<Location> {
  const fd = makeFormData({ ...payload, _method: "PUT" }, ["image"]);
  const res = await api.post(`/v1/locations/${id}`, fd, {
    headers: { "Content-Type": "multipart/form-data" },
  });
  return res.data as Location;
}

export async function deleteLocation(id: number): Promise<void> {
  await api.delete(`/v1/locations/${id}`);
}

export default api;
