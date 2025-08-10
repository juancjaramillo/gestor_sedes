import { useCallback, useEffect, useState, type ChangeEvent } from "react";
import {
  Alert, Box, Button, CircularProgress, Pagination, Stack, TextField, Typography,
} from "@mui/material";
import { listLocations } from "../lib/api";
import type { Location, Paginated } from "../types/location";
import LocationCard from "./LocationCard";

type Props = { reloadKey?: number; onEdit?: (item: Location) => void };

export default function LocationList({ reloadKey = 0, onEdit }: Props) {
  const [items, setItems] = useState<Location[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [page, setPage] = useState<number>(1);
  const [lastPage, setLastPage] = useState<number>(1);
  const [filters, setFilters] = useState<{ name: string; code: string }>({ name: "", code: "" });

  const fetchData = useCallback(async (p: number = 1) => {
    setLoading(true);
    setError(null);
    try {
      const params: Record<string, string | number> = { page: p, per_page: 6 };
      if (filters.name) params.name = filters.name;
      if (filters.code) params.code = filters.code;
      const res: Paginated<Location> = await listLocations(params);
      setItems(res.data);
      setLastPage(res.meta.last_page);
    } catch (e: any) {
      setError(e?.message ?? "Error desconocido");
    } finally {
      setLoading(false);
    }
  }, [filters.name, filters.code]);

  useEffect(() => { setPage(1); fetchData(1); }, [reloadKey, fetchData]);
  useEffect(() => { fetchData(page); }, [page, fetchData]);

  return (
    <Box sx={{ p: 2, maxWidth: 1000, mx: "auto" }}>
      <Stack direction={{ xs: "column", sm: "row" }} spacing={2} sx={{ mb: 2 }}>
        <TextField
          placeholder="Filter by name"
          value={filters.name}
          onChange={(e) => setFilters((f) => ({ ...f, name: e.target.value }))}
        />
        <TextField
          placeholder="Filter by code"
          value={filters.code}
          onChange={(e) => setFilters((f) => ({ ...f, code: e.target.value.toUpperCase() }))}
        />
        <Button onClick={() => fetchData(1)} variant="outlined">SEARCH</Button>
      </Stack>

      {error && <Alert severity="error" sx={{ mb: 2 }}>{error}</Alert>}
      {loading && <Stack alignItems="center" sx={{ mt: 6, mb: 6 }}><CircularProgress /></Stack>}
      {!loading && items.length === 0 && <Typography>No results.</Typography>}

      {items.map((it) => (
        <Box key={it.id} sx={{ display: "flex", alignItems: "center", mb: 2 }}>
          <LocationCard item={it} onEdit={onEdit} />
        </Box>
      ))}

      <Pagination
        sx={{ mt: 2 }}
        page={page}
        count={lastPage}
        onChange={(_e: ChangeEvent<unknown>, v: number) => setPage(v)}
      />
    </Box>
  );
}
