import { render, screen, waitFor } from "@testing-library/react";
import LocationList from "../components/LocationList";

jest.mock("../lib/api", () => ({
  __esModule: true,
  default: {
    get: jest.fn().mockRejectedValue(new Error("Server Error")),
  },
}));

describe("LocationList error", () => {
  test("muestra Alert cuando la API falla", async () => {
    render(<LocationList />);
    await waitFor(() => {
      expect(screen.getByText(/server error/i)).toBeInTheDocument();
    });
  });
});
