import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { useState } from "react";
import { Box, Button, Stack, TextField, Typography, Alert } from "@mui/material";
import { createLocationFD } from "@/lib/api";
export default function LocationForm({ onCreated, onSuccess }) {
    const [code, setCode] = useState("");
    const [name, setName] = useState("");
    const [file, setFile] = useState(null);
    const [preview, setPreview] = useState(null);
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(false);
    function onFile(e) {
        const f = e.target.files?.[0] || null;
        setFile(f);
        setPreview(f ? URL.createObjectURL(f) : null);
    }
    async function onSubmit(e) {
        e.preventDefault();
        setError(null);
        setLoading(true);
        try {
            const fd = new FormData();
            fd.append("code", code.trim());
            fd.append("name", name.trim());
            if (file)
                fd.append("image", file);
            const created = await createLocationFD(fd);
            setCode("");
            setName("");
            setFile(null);
            setPreview(null);
            onCreated?.({ code: created.code, name: created.name, image: created.image ?? null });
            onSuccess?.();
        }
        catch (err) {
            const msg = typeof err === "object" && err && "message" in err
                ? String(err.message || "Error")
                : "Error";
            setError(msg);
        }
        finally {
            setLoading(false);
        }
    }
    return (_jsx(Box, { component: "form", onSubmit: onSubmit, sx: { mb: 3 }, children: _jsxs(Stack, { spacing: 2, children: [error && _jsx(Alert, { severity: "error", children: error }), _jsx(TextField, { label: "C\u00F3digo", required: true, value: code, onChange: (e) => setCode(e.target.value), inputProps: { maxLength: 10 } }), _jsx(TextField, { label: "Nombre", required: true, value: name, onChange: (e) => setName(e.target.value), inputProps: { maxLength: 100 } }), _jsxs(Box, { children: [_jsx(Typography, { variant: "body2", gutterBottom: true, children: "Imagen (opcional)" }), _jsx("input", { type: "file", accept: "image/*", "aria-label": "Imagen", onChange: onFile }), preview && (_jsx(Box, { sx: { mt: 1 }, children: _jsx("img", { src: preview, alt: "preview", style: { width: 180, height: 120, objectFit: "cover", borderRadius: 8 } }) }))] }), _jsx(Box, { children: _jsx(Button, { disabled: loading, type: "submit", variant: "contained", children: loading ? "Creando..." : "Crear" }) })] }) }));
}
