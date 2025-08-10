import { useEffect, useRef, useState } from "react";
import { Box, Button, Stack, TextField, Alert, Typography } from "@mui/material";
import Pagination from "@mui/material/Pagination";
import { listLocations } from "@/lib/api";
import type { Location, Paginated } from "@/types/location";
import LocationCard from "./LocationCard";

type Filters = { name?: string; code?: string; page: number; per_page: number };

export default function LocationList() {
  const [items, setItems] = useState<Location[]>([]);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  const [name, setName] = useState("");
  const [code, setCode] = useState("");
  const [loading, setLoading] = useState(false);
  const [err, setErr] = useState<string | null>(null);
  const didFirst = useRef(false);

  function computeLastPage(meta: Paginated<Location>["meta"]): number {
    const m = meta as unknown as {
      last_page?: number;
      total?: number;
      per_page?: number;
    };
    if (m?.last_page && m.last_page > 0) return m.last_page;
    if (m?.total && m?.per_page) return Math.max(1, Math.ceil(m.total / m.per_page));
    return 1;
  }

  async function fetchData(f: Filters) {
    setLoading(true);
    setErr(null);
    try {
      const res: Paginated<Location> = await listLocations(f);
      setItems(Array.isArray(res.data) ? res.data : []);
      const lp = computeLastPage(res.meta);
      setLastPage(lp);

      if (f.page > lp) {
        const fixed = { ...f, page: lp };
        setPage(lp);
        const res2: Paginated<Location> = await listLocations(fixed);
        setItems(Array.isArray(res2.data) ? res2.data : []);
        setLastPage(computeLastPage(res2.meta));
      }
    } catch (e) {
      const msg = e instanceof Error ? e.message : "Error";
      setErr(msg);
      setItems([]);
      setLastPage(1);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    if (didFirst.current) return;
    didFirst.current = true;
    fetchData({ page: 1, per_page: 6 });
  }, []);

  function onSearch() {
    setPage(1);
    fetchData({ page: 1, per_page: 6, name, code });
  }

  function onChangePage(_e: React.ChangeEvent<unknown>, value: number) {
    setPage(value);
    fetchData({ page: value, per_page: 6, name, code });
  }

  return (
    <Box>
      <Stack direction={{ xs: "column", sm: "row" }} spacing={2} sx={{ mb: 2 }}>
        <TextField
          placeholder="Filtrar por nombre"
          value={name}
          onChange={(e: React.ChangeEvent<HTMLInputElement>) => setName(e.target.value)}
          fullWidth
        />
        <TextField
          placeholder="Filtrar por código"
          value={code}
          onChange={(e: React.ChangeEvent<HTMLInputElement>) => setCode(e.target.value)}
          fullWidth
        />
        <Button variant="outlined" onClick={onSearch} sx={{ minWidth: 120 }}>
          BUSCAR
        </Button>
      </Stack>

      {err && <Alert severity="error" sx={{ mb: 2 }}>{err}</Alert>}

      {loading ? (
        <Typography>Cargando...</Typography>
      ) : items.length === 0 ? (
        <Typography>Sin resultados.</Typography>
      ) : (
        <Box>
          {items.map((it) => (
            <LocationCard
              key={it.id}
              item={it}
              onUpdated={() => fetchData({ page, per_page: 6, name, code })}
            />
          ))}
        </Box>
      )}

      <Stack alignItems="center" sx={{ mt: 2 }}>
        <Pagination
          count={Math.max(1, lastPage)}
          page={page}
          onChange={onChangePage}
          showFirstButton
          showLastButton
          shape="rounded"
          size="medium"
          siblingCount={1}
          boundaryCount={1}
          sx={{
            "& .MuiPaginationItem-root": { borderRadius: "12px", minWidth: 36, height: 36 },
          }}
        />
      </Stack>
    </Box>
  );
}
