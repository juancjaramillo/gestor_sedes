import { Container, Typography } from "@mui/material";
import LocationList from "./components/LocationList";
import LocationForm from "./components/LocationForm";

export default function App() {
  return (
    <Container sx={{ py: 3 }}>
      <Typography variant="h4" sx={{ mb: 2 }}>
        Gestor de Sedes
      </Typography>
      <LocationForm onCreated={() => window.location.reload()} />
      <LocationList />
    </Container>
  );
}
