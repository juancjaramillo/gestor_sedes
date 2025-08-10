import { Card, CardContent, Typography, Button, Stack } from "@mui/material";
import type { Location } from "../types/location";

type Props = { item: Location; onEdit?: (item: Location) => void };

export default function LocationCard({ item, onEdit }: Props) {
  return (
    <Card sx={{ mb: 2 }}>
      <CardContent>
        <Stack direction="row" spacing={2} alignItems="center">
          {item.image_url && (
            <img
              src={item.image_url}
              alt={item.name}
              style={{ width: 120, height: 80, objectFit: "cover", borderRadius: 6 }}
            />
          )}
          <Stack flex={1}>
            <Typography variant="h6">
              {item.name} ({item.code})
            </Typography>
          </Stack>
          {onEdit && (
            <Button size="small" variant="outlined" onClick={() => onEdit(item)}>
              Editar
            </Button>
          )}
        </Stack>
      </CardContent>
    </Card>
  );
}
