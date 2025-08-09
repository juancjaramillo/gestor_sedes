export interface Location {
  id: number;
  code: string;
  name: string;
  image?: string | null;
  created_at?: string;
}

export interface Paginated<T> {
  data: T[];
  meta: { current_page: number; per_page: number; total: number; last_page: number };
}
