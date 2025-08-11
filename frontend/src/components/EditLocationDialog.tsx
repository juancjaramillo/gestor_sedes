import { useEffect, useState } from "react";
import {
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  Button,
  Stack,
  TextField,
  Alert,
} from "@mui/material";
import { updateLocationFD } from "../lib/api";
import type { Location } from "../types/location";

type Props = {
  open: boolean;
  onClose: () => void;
  item: Location;
  onUpdated: () => void;
};

function extractApiErrorMessage(e: unknown): string {
  if (typeof e === "object" && e !== null) {
    const anyErr = e as Record<string, unknown>;
    const resp = anyErr.response as Record<string, unknown> | undefined;
    const data =
      (resp?.data as Record<string, unknown>) ??
      (anyErr.data as Record<string, unknown>) ??
      {};
    const nested =
      (data.errors as Record<string, string[] | undefined>) ??
      ((data.error as Record<string, unknown>)?.["details"] as
        | Record<string, string[] | undefined>
        | undefined);

    const pick = (k: string) =>
      nested && Array.isArray(nested[k]) ? nested[k]![0] : undefined;

    return (
      pick("image") ||
      pick("code") ||
      pick("name") ||
      (data.message as string | undefined) ||
      ((data.error as Record<string, unknown>)?.["message"] as
        | string
        | undefined) ||
      (anyErr.message as string | undefined) ||
      "Error"
    );
  }
  return "Error";
}

export default function EditLocationDialog({
  open,
  onClose,
  item,
  onUpdated,
}: Props) {
  const [code, setCode] = useState(item.code);
  const [name, setName] = useState(item.name);
  const [file, setFile] = useState<File | null>(null);
  const [preview, setPreview] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);
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

  const onFile = (e: React.ChangeEvent<HTMLInputElement>) => {
    const f = e.target.files?.[0] ?? null;
    setFile(f);
    setPreview(f ? URL.createObjectURL(f) : null);
  };

  const onSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setError(null);
    setLoading(true);
    try {
      const fd = new FormData();
      fd.append("code", code.trim());
      fd.append("name", name.trim());
      if (file) fd.append("image", file);

      await updateLocationFD(item.id, fd);

      onUpdated();
      onClose();
    } catch (err: unknown) {
      setError(extractApiErrorMessage(err));
    } finally {
      setLoading(false);
    }
  };

  return (
    <Dialog open={open} onClose={onClose} fullWidth maxWidth="sm">
      <form onSubmit={onSubmit} encType="multipart/form-data">
        <DialogTitle>Editar sede</DialogTitle>
        <DialogContent>
          <Stack spacing={2} sx={{ mt: 1 }}>
            {error && <Alert severity="error">{error}</Alert>}

            <TextField
              label="Código"
              value={code}
              onChange={(e) => setCode(e.target.value)}
              required
              inputProps={{ maxLength: 50 }}
            />

            <TextField
              label="Nombre"
              value={name}
              onChange={(e) => setName(e.target.value)}
              required
              inputProps={{ maxLength: 255 }}
            />

            <input type="file" accept="image/*" onChange={onFile} />

            {preview ? (
              <img
                src={preview}
                alt="preview"
                style={{
                  width: 220,
                  height: 140,
                  objectFit: "cover",
                  borderRadius: 8,
                }}
              />
            ) : item.image ? (
              <img
                src={item.image}
                alt={item.name}
                style={{
                  width: 220,
                  height: 140,
                  objectFit: "cover",
                  borderRadius: 8,
                }}
              />
            ) : null}
          </Stack>
        </DialogContent>

        <DialogActions>
          <Button onClick={onClose} disabled={loading}>
            Cancelar
          </Button>
          <Button type="submit" variant="contained" disabled={loading}>
            {loading ? "Guardando..." : "Guardar"}
          </Button>
        </DialogActions>
      </form>
    </Dialog>
  );
}
