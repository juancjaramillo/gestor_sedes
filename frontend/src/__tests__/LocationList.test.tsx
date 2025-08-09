import { render, screen, waitFor } from "@testing-library/react";
import LocationList from "../components/LocationList";

// MOCK para evitar evaluar api.ts (import.meta) y controlar la promesa
const mockGet = jest.fn().mockResolvedValue({
  data: {
    data: [],
    meta: { current_page: 1, per_page: 6, total: 0, last_page: 1 },
  },
});

jest.mock("../lib/api", () => ({
  __esModule: true,
  default: { get: (...args: any[]) => mockGet(...args) },
}));

test("renderiza filtros de búsqueda", async () => {
  render(<LocationList />);

  // Espera a que el efecto haga la llamada; esto se ejecuta dentro de act()
  await waitFor(() => expect(mockGet).toHaveBeenCalled());

  expect(screen.getByLabelText(/filter by name/i)).toBeInTheDocument();
  expect(screen.getByLabelText(/filter by code/i)).toBeInTheDocument();
});
