// C:\xampp82\htdocs\gestor_sedes\frontend\src\types\location.ts
export type Location = {
  id: number;
  code: string;
  name: string;
  image: string | null;
  image_url?: string | null;
  created_at?: string | null;
};

export type Paginated<T> = {
  data: T[];
  meta: {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
  };
};

// <-- añade esto
export type ApiError = { message: string };
