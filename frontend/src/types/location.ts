export type Location = {
  id: number;
  code: string;
  name: string;
  image?: string | null;
};

export type Paginated<T> = {
  data: T[];
  meta: { last_page: number };
};
