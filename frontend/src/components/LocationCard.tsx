import { useState } from "react";
import { Card, CardContent, Typography, Button, Stack } from "@mui/material";
import type { Location } from "../types/location";
import EditLocationDialog from "./EditLocationDialog";

export default function LocationCard({ item, onUpdated }: { item: Location; onUpdated: () => void }) {
  const [open, setOpen] = useState(false);
  return (
    <>
      <Card sx={{ mb: 2 }}>
        <CardContent>
          <Stack direction="row" justifyContent="space-between" alignItems="center">
            <Typography variant="h6">
              {item.name} ({item.code})
            </Typography>
            <Button size="small" variant="outlined" onClick={() => setOpen(true)}>Editar</Button>
          </Stack>
          {item.image ? (
            <img
              src={item.image}
              alt={item.name}
              style={{ width: 220, height: 140, objectFit: "cover", borderRadius: 8, marginTop: 8 }}
            />
          ) : (
            <Typography variant="body2" color="text.secondary" sx={{ mt: 1 }}>
              Sin imagen
            </Typography>
          )}
        </CardContent>
      </Card>

      <EditLocationDialog
        open={open}
        onClose={() => setOpen(false)}
        item={item}
        onUpdated={onUpdated}
      />
    </>
  );
}
