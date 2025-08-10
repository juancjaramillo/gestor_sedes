import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { useEffect, useRef, useState } from "react";
import { Box, Button, Stack, TextField, Alert, Typography } from "@mui/material";
import Pagination from "@mui/material/Pagination";
import { listLocations } from "@/lib/api";
import LocationCard from "./LocationCard";
export default function LocationList() {
    const [items, setItems] = useState([]);
    const [page, setPage] = useState(1);
    const [lastPage, setLastPage] = useState(1);
    const [name, setName] = useState("");
    const [code, setCode] = useState("");
    const [loading, setLoading] = useState(false);
    const [err, setErr] = useState(null);
    const didFirst = useRef(false);
    function computeLastPage(meta) {
        const m = meta;
        if (m?.last_page && m.last_page > 0)
            return m.last_page;
        if (m?.total && m?.per_page)
            return Math.max(1, Math.ceil(m.total / m.per_page));
        return 1;
    }
    async function fetchData(f) {
        setLoading(true);
        setErr(null);
        try {
            const res = await listLocations(f);
            setItems(Array.isArray(res.data) ? res.data : []);
            const lp = computeLastPage(res.meta);
            setLastPage(lp);
            if (f.page > lp) {
                const fixed = { ...f, page: lp };
                setPage(lp);
                const res2 = await listLocations(fixed);
                setItems(Array.isArray(res2.data) ? res2.data : []);
                setLastPage(computeLastPage(res2.meta));
            }
        }
        catch (e) {
            const msg = e instanceof Error ? e.message : "Error";
            setErr(msg);
            setItems([]);
            setLastPage(1);
        }
        finally {
            setLoading(false);
        }
    }
    useEffect(() => {
        if (didFirst.current)
            return;
        didFirst.current = true;
        fetchData({ page: 1, per_page: 6 });
    }, []);
    function onSearch() {
        setPage(1);
        fetchData({ page: 1, per_page: 6, name, code });
    }
    function onChangePage(_e, value) {
        setPage(value);
        fetchData({ page: value, per_page: 6, name, code });
    }
    return (_jsxs(Box, { children: [_jsxs(Stack, { direction: { xs: "column", sm: "row" }, spacing: 2, sx: { mb: 2 }, children: [_jsx(TextField, { placeholder: "Filtrar por nombre", value: name, onChange: (e) => setName(e.target.value), fullWidth: true }), _jsx(TextField, { placeholder: "Filtrar por c\u00F3digo", value: code, onChange: (e) => setCode(e.target.value), fullWidth: true }), _jsx(Button, { variant: "outlined", onClick: onSearch, sx: { minWidth: 120 }, children: "BUSCAR" })] }), err && _jsx(Alert, { severity: "error", sx: { mb: 2 }, children: err }), loading ? (_jsx(Typography, { children: "Cargando..." })) : items.length === 0 ? (_jsx(Typography, { children: "Sin resultados." })) : (_jsx(Box, { children: items.map((it) => (_jsx(LocationCard, { item: it, onUpdated: () => fetchData({ page, per_page: 6, name, code }) }, it.id))) })), _jsx(Stack, { alignItems: "center", sx: { mt: 2 }, children: _jsx(Pagination, { count: Math.max(1, lastPage), page: page, onChange: onChangePage, showFirstButton: true, showLastButton: true, shape: "rounded", size: "medium", siblingCount: 1, boundaryCount: 1, sx: {
                        "& .MuiPaginationItem-root": { borderRadius: "12px", minWidth: 36, height: 36 },
                    } }) })] }));
}
