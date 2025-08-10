import { jsxs as _jsxs, jsx as _jsx, Fragment as _Fragment } from "react/jsx-runtime";
import { useState } from "react";
import { Card, CardContent, Typography, Button, Stack } from "@mui/material";
import EditLocationDialog from "./EditLocationDialog";
export default function LocationCard({ item, onUpdated }) {
    const [open, setOpen] = useState(false);
    return (_jsxs(_Fragment, { children: [_jsx(Card, { sx: { mb: 2 }, children: _jsxs(CardContent, { children: [_jsxs(Stack, { direction: "row", justifyContent: "space-between", alignItems: "center", children: [_jsxs(Typography, { variant: "h6", children: [item.name, " (", item.code, ")"] }), _jsx(Button, { size: "small", variant: "outlined", onClick: () => setOpen(true), children: "Editar" })] }), item.image ? (_jsx("img", { src: item.image, alt: item.name, style: { width: 220, height: 140, objectFit: "cover", borderRadius: 8, marginTop: 8 } })) : (_jsx(Typography, { variant: "body2", color: "text.secondary", sx: { mt: 1 }, children: "Sin imagen" }))] }) }), _jsx(EditLocationDialog, { open: open, onClose: () => setOpen(false), item: item, onUpdated: onUpdated })] }));
}
