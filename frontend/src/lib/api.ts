import axios from "axios";
import type { Location, Paginated, ApiError } from "../types/location";

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: { "x-api-key": import.meta.env.VITE_API_KEY as string },
});

api.interceptors.response.use(
  (r) => r,
  (error) => {
    const r = error?.response;
    if (r?.status === 422) {
      const bag = r.data?.errors;
      let msg = r.data?.message ?? "Parámetros inválidos.";
      if (bag && typeof bag === "object") {
        const planos = (Object.values(bag).flat() as string[]).join(" | ");
        if (planos) msg = `Parámetros inválidos: ${planos}`;
      }
      return Promise.reject({ message: msg } as ApiError);
    }
    const msg =
      r?.data?.error?.message ??
      r?.data?.message ??
      error.message ??
      "Error desconocido";
    return Promise.reject({ message: msg } as ApiError);
  }
);

export async function listLocations(params: {
  name?: string; code?: string; page?: number; per_page?: number;
}) {
  const res = await api.get("/v1/locations", { params });
  return res.data as Paginated<Location>;
}

export async function createLocationFD(fd: FormData) {
  const res = await api.post("/v1/locations", fd);
  return res.data as { data: Location };
}

export async function updateLocationFD(id: number, fd: FormData) {
  // usa POST si tu update es POST; si es PUT cámbialo
  const res = await api.post(`/v1/locations/${id}`, fd);
  return res.data as { data: Location };
}

export default api;
