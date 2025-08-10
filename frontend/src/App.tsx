import React, { useState } from "react";
import { Container, Typography, Divider, Dialog, DialogTitle, DialogContent } from "@mui/material";
import LocationForm from "./components/LocationForm";
import LocationList from "./components/LocationList";
import type { Location } from "./types/location";

export default function App() {
  const [reloadKey, setReloadKey] = useState(0);
  const [editing, setEditing] = useState<Location | null>(null);
  const [open, setOpen] = useState(false);

  const handleCreated = () => setReloadKey((k) => k + 1); // recarga lista (crear)
  const handleEditClick = (l: Location) => { setEditing(l); setOpen(true); };
  const handleEdited = () => { setOpen(false); setEditing(null); setReloadKey((k) => k + 1); };

  return (
    <Container maxWidth="md" sx={{ py: 3 }}>
      <Typography variant="h4" gutterBottom>Gestor de Sedes</Typography>

      {/* Crear */}
      <LocationForm onSuccess={handleCreated} />

      <Divider sx={{ my: 2 }} />

      {/* Listado + botón Editar */}
      <LocationList reloadKey={reloadKey} onEdit={handleEditClick} />

      {/* Diálogo de edición */}
      <Dialog open={open} onClose={() => setOpen(false)} fullWidth maxWidth="sm">
        <DialogTitle>Editar sede</DialogTitle>
        <DialogContent>
          <LocationForm editing={editing ?? undefined} onSuccess={handleEdited} />
        </DialogContent>
      </Dialog>
    </Container>
  );
}
