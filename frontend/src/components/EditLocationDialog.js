import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { useEffect, useState } from "react";
import { Dialog, DialogTitle, DialogContent, DialogActions, Button, Stack, TextField, Alert } from "@mui/material";
import { updateLocationFD } from "../lib/api";
function extractApiErrorMessage(e) {
    if (typeof e === "object" && e !== null) {
        const anyErr = e;
        const resp = anyErr.response;
        const data = resp?.data ?? anyErr.data ?? {};
        const nested = data.errors ??
            data.error?.["details"];
        const pick = (k) => (nested && Array.isArray(nested[k]) ? nested[k][0] : undefined);
        return (pick("image") ||
            pick("code") ||
            pick("name") ||
            data.message ||
            data.error?.["message"] ||
            anyErr.message ||
            "Error");
    }
    return "Error";
}
export default function EditLocationDialog({ open, onClose, item, onUpdated }) {
    const [code, setCode] = useState(item.code);
    const [name, setName] = useState(item.name);
    const [file, setFile] = useState(null);
    const [preview, setPreview] = useState(null);
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(false);
    useEffect(() => {
        if (open) {
            setError(null);
            setPreview(null);
            setFile(null);
            setCode(item.code);
            setName(item.name);
        }
    }, [open, item]);
    const onFile = (e) => {
        const f = e.target.files?.[0] ?? null;
        setFile(f);
        setPreview(f ? URL.createObjectURL(f) : null);
    };
    const onSubmit = async (e) => {
        e.preventDefault();
        setError(null);
        setLoading(true);
        try {
            const fd = new FormData();
            fd.append("code", code.trim());
            fd.append("name", name.trim());
            if (file)
                fd.append("image", file);
            await updateLocationFD(item.id, fd);
            onUpdated();
            onClose();
        }
        catch (err) {
            setError(extractApiErrorMessage(err));
        }
        finally {
            setLoading(false);
        }
    };
    return (_jsx(Dialog, { open: open, onClose: onClose, fullWidth: true, maxWidth: "sm", children: _jsxs("form", { onSubmit: onSubmit, children: [_jsx(DialogTitle, { children: "Editar sede" }), _jsx(DialogContent, { children: _jsxs(Stack, { spacing: 2, sx: { mt: 1 }, children: [error && _jsx(Alert, { severity: "error", children: error }), _jsx(TextField, { label: "C\u00F3digo", value: code, onChange: (e) => setCode(e.target.value), required: true }), _jsx(TextField, { label: "Nombre", value: name, onChange: (e) => setName(e.target.value), required: true }), _jsx("input", { type: "file", accept: "image/*", onChange: onFile }), preview ? (_jsx("img", { src: preview, alt: "preview", style: { width: 220, height: 140, objectFit: "cover", borderRadius: 8 } })) : item.image ? (_jsx("img", { src: item.image, alt: item.name, style: { width: 220, height: 140, objectFit: "cover", borderRadius: 8 } })) : null] }) }), _jsxs(DialogActions, { children: [_jsx(Button, { onClick: onClose, disabled: loading, children: "Cancelar" }), _jsx(Button, { type: "submit", variant: "contained", disabled: loading, children: loading ? "Guardando..." : "Guardar" })] })] }) }));
}
