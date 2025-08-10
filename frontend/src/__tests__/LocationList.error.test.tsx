import { render, screen, waitFor } from "@testing-library/react";
import LocationList from "@/components/LocationList";

jest.mock("@/lib/api", () => ({
  __esModule: true,
  listLocations: jest.fn(),
}));

import { listLocations } from "@/lib/api";

test("muestra Alert cuando la API falla", async () => {
  (listLocations as jest.Mock).mockRejectedValue(new Error("Server error"));

  render(<LocationList />);

  await waitFor(() => {
    expect(screen.getByRole("alert")).toBeInTheDocument();
    expect(screen.getByText(/server error/i)).toBeInTheDocument();
  });
});
