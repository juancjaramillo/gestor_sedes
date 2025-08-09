import { useEffect, useState } from "react";
import { Alert, Box, CircularProgress, Pagination, Stack, TextField, Typography, Button } from "@mui/material";
import api from "../lib/api";
import type { Location, Paginated } from "../types/location";
import LocationCard from "./LocationCard";

export default function LocationList() {
  const [items, setItems] = useState<Location[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  const [filters, setFilters] = useState({ name: "", code: "" });

  const fetchData = async (p = 1) => {
    setLoading(true);
    setError(null);
    try {
      const params: any = { page: p, per_page: 6 };
      if (filters.name) params.name = filters.name;
      if (filters.code) params.code = filters.code;
      const res = await api.get<Paginated<Location>>("/v1/locations", { params });
      setItems(res.data.data);
      setLastPage(res.data.meta.last_page);
    } catch (e: any) {
      setError(e.message);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchData(page);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [page]);

  return (
    <Box>
      <Stack direction={{ xs: "column", sm: "row" }} spacing={2} sx={{ mb: 2 }}>
        <TextField
          label="Filter by name"
          value={filters.name}
          onChange={(e) => setFilters({ ...filters, name: e.target.value })}
        />
        <TextField
          label="Filter by code"
          value={filters.code}
          onChange={(e) => setFilters({ ...filters, code: e.target.value })}
        />
        <Button variant="outlined" onClick={() => { setPage(1); fetchData(1); }}>
          Search
        </Button>
      </Stack>

      {loading && <CircularProgress />}
      {error && <Alert severity="error">{error}</Alert>}
      {!loading && !error && items.length === 0 && <Typography>No results</Typography>}

      {items.map((it) => (
        <LocationCard key={it.id} item={it} />
      ))}

      <Pagination sx={{ mt: 2 }} page={page} count={lastPage} onChange={(_, v) => setPage(v)} />
    </Box>
  );
}
