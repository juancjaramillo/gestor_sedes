export interface Location {
  id: number;
  code: string;
  name: string;
  image?: string | null;     // ruta relativa en storage (backend)
  image_url?: string | null; // URL absoluta servida por backend
  created_at?: string;
}

export interface Paginated<T> {
  data: T[];
  meta: {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
  };
}
