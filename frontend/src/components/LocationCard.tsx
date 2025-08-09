import { Card, CardContent, Typography } from "@mui/material";
import type { Location } from "../types/location";

export default function LocationCard({ item }: { item: Location }) {
  return (
    <Card sx={{ mb: 2 }}>
      <CardContent>
        <Typography variant="h6">
          {item.name} ({item.code})
        </Typography>
        {item.image && (
          <img src={item.image} alt={item.name} style={{ maxWidth: 300 }} />
        )}
      </CardContent>
    </Card>
  );
}
