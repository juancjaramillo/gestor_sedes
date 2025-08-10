import { render, screen, waitFor } from "@testing-library/react";
import * as api from "../lib/api";
import LocationList from "../components/LocationList";

test("renderiza filtros de búsqueda", async () => {
  jest.spyOn(api, "listLocations").mockResolvedValue({
    data: [],
    meta: { current_page: 1, per_page: 6, total: 0, last_page: 1 },
  });

  render(<LocationList />);

  await waitFor(() => expect(api.listLocations).toHaveBeenCalled());

  expect(screen.getByPlaceholderText(/filter by name/i)).toBeInTheDocument();
  expect(screen.getByPlaceholderText(/filter by code/i)).toBeInTheDocument();
});
