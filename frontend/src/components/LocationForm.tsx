import { useEffect, useMemo, useRef, useState } from "react";
import {
  Alert,
  Box,
  Button,
  CircularProgress,
  Stack,
  TextField,
  Typography,
} from "@mui/material";
import { createLocationFD, updateLocationFD } from "../lib/api";
import type { Location } from "../types/location";

type Props = {
  editing?: Location | null;
  onSuccess: () => void;
};

export default function LocationForm({ editing = null, onSuccess }: Props) {
  const [code, setCode] = useState(editing?.code ?? "");
  const [name, setName] = useState(editing?.name ?? "");
  const [file, setFile] = useState<File | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const inputRef = useRef<HTMLInputElement | null>(null);

  useEffect(() => {
    setCode(editing?.code ?? "");
    setName(editing?.name ?? "");
    setFile(null);
  }, [editing?.id, editing?.code, editing?.name]);

  const previewUrl = useMemo(() => {
    if (file) return URL.createObjectURL(file);
    return editing?.image_url ?? null;
  }, [file, editing?.image_url]);

  async function onSubmit(e: React.FormEvent) {
    e.preventDefault();
    setError(null);
    if (!code || !name) {
      setError("Code y Name son obligatorios");
      return;
    }
    setLoading(true);
    try {
      const fd = new FormData();
      fd.append("code", code);
      fd.append("name", name);
      if (file) fd.append("image", file);

      if (editing?.id) await updateLocationFD(editing.id, fd);
      else await createLocationFD(fd);

      if (inputRef.current) inputRef.current.value = "";
      setFile(null);
      setCode("");
      setName("");
      onSuccess();
    } catch (err: unknown) {
      const message = err instanceof Error ? err.message : "Error al guardar";
      setError(message);
    } finally {
      setLoading(false);
    }
  }

  return (
    <Box component="form" onSubmit={onSubmit}>
      <Stack spacing={2}>
        {error && <Alert severity="error">{error}</Alert>}
        <TextField
          label="Code"
          value={code}
          onChange={(e) => setCode(e.target.value.toUpperCase())}
          inputProps={{ maxLength: 20 }}
          required
        />
        <TextField
          label="Name"
          value={name}
          onChange={(e) => setName(e.target.value)}
          inputProps={{ maxLength: 100 }}
          required
        />
        <Box>
          <Typography variant="body2" gutterBottom>Imagen (opcional)</Typography>
          <input
            ref={inputRef}
            type="file"
            accept="image/*"
            aria-label="Imagen"            
            onChange={(e) => setFile(e.target.files?.[0] ?? null)}
          />
          {previewUrl && (
            <Box mt={1}>
              <img
                src={previewUrl}
                alt="preview"
                style={{ width: 180, height: 120, objectFit: "cover", borderRadius: 8 }}
              />
            </Box>
          )}
        </Box>
        <Box>
          <Button type="submit" variant="contained" disabled={loading}>
            {loading ? <CircularProgress size={20} /> : (editing ? "Actualizar" : "Crear")}
          </Button>
        </Box>
      </Stack>
    </Box>
  );
}
