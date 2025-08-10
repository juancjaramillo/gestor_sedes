import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { Container, Typography } from "@mui/material";
import LocationList from "./components/LocationList";
import LocationForm from "./components/LocationForm";
export default function App() {
    return (_jsxs(Container, { sx: { py: 3 }, children: [_jsx(Typography, { variant: "h4", sx: { mb: 2 }, children: "Gestor de Sedes" }), _jsx(LocationForm, { onCreated: () => window.location.reload() }), _jsx(LocationList, {})] }));
}
