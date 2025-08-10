import { useState } from "react";
import { Box, Button, Stack, TextField, Typography, Alert } from "@mui/material";
import { createLocationFD } from "@/lib/api";

type Props = {
  onCreated?: (loc: { code: string; name: string; image?: string | null }) => void;
  onSuccess?: () => void;
};

export default function LocationForm({ onCreated, onSuccess }: Props) {
  const [code, setCode] = useState("");
  const [name, setName] = useState("");
  const [file, setFile] = useState<File | null>(null);
  const [preview, setPreview] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  function onFile(e: React.ChangeEvent<HTMLInputElement>) {
    const f = e.target.files?.[0] || null;
    setFile(f);
    setPreview(f ? URL.createObjectURL(f) : null);
  }

  async function onSubmit(e: React.FormEvent) {
    e.preventDefault();
    setError(null);
    setLoading(true);
    try {
      const fd = new FormData();
      fd.append("code", code.trim());
      fd.append("name", name.trim());
      if (file) fd.append("image", file);

      const created = await createLocationFD(fd);

      setCode("");
      setName("");
      setFile(null);
      setPreview(null);

      onCreated?.({ code: created.code, name: created.name, image: created.image ?? null });
      onSuccess?.();
    } catch (err: unknown) {
      const msg =
        typeof err === "object" && err && "message" in err
          ? String((err as { message?: string }).message || "Error")
          : "Error";
      setError(msg);
    } finally {
      setLoading(false);
    }
  }

  return (
    <Box component="form" onSubmit={onSubmit} sx={{ mb: 3 }}>
      <Stack spacing={2}>
        {error && <Alert severity="error">{error}</Alert>}

        <TextField
          label="Código"
          required
          value={code}
          onChange={(e) => setCode(e.target.value)}
          inputProps={{ maxLength: 10 }}
        />
        <TextField
          label="Nombre"
          required
          value={name}
          onChange={(e) => setName(e.target.value)}
          inputProps={{ maxLength: 100 }}
        />

        <Box>
          <Typography variant="body2" gutterBottom>Imagen (opcional)</Typography>
          <input type="file" accept="image/*" aria-label="Imagen" onChange={onFile} />
          {preview && (
            <Box sx={{ mt: 1 }}>
              <img
                src={preview}
                alt="preview"
                style={{ width: 180, height: 120, objectFit: "cover", borderRadius: 8 }}
              />
            </Box>
          )}
        </Box>

        <Box>
          <Button disabled={loading} type="submit" variant="contained">
            {loading ? "Creando..." : "Crear"}
          </Button>
        </Box>
      </Stack>
    </Box>
  );
}
