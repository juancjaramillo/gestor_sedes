import { render, screen } from "@testing-library/react";
import LocationList from "@/components/LocationList";

jest.mock("@/lib/api", () => ({
  __esModule: true,
  listLocations: jest.fn(),
}));

import { listLocations } from "@/lib/api";

test("renderiza filtros de búsqueda", async () => {
  (listLocations as jest.Mock).mockResolvedValue({
    data: [],
    meta: { current_page: 1, last_page: 1, per_page: 6, total: 0 },
  });

  render(<LocationList />);

  expect(screen.getByPlaceholderText(/filtrar por nombre/i)).toBeInTheDocument();
  expect(screen.getByPlaceholderText(/filtrar por código/i)).toBeInTheDocument();
  expect(screen.getByRole("button", { name: /buscar/i })).toBeInTheDocument();
});
