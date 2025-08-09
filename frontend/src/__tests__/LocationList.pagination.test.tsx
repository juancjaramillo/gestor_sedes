import { render, screen, waitFor, fireEvent } from "@testing-library/react";
import LocationList from "../components/LocationList";

const mockGet = jest.fn();

jest.mock("../lib/api", () => ({
  __esModule: true,
  default: {
    get: (...args: any[]) => mockGet(...args),
  },
}));

const pageResponse = (page: number) => ({
  data: {
    data: [{ id: page, code: `P${page}`, name: `Page ${page}` }],
    meta: { current_page: page, per_page: 6, total: 12, last_page: 2 },
  },
});

describe("LocationList pagination", () => {
  test("cambia de página y llama API con la nueva page", async () => {
    mockGet
      .mockResolvedValueOnce(pageResponse(1)) // inicial
      .mockResolvedValueOnce(pageResponse(2)); // al cambiar

    render(<LocationList />);

    await waitFor(() => {
      expect(screen.getByText(/Page 1/i)).toBeInTheDocument();
    });

    const nextBtn = screen.getByRole("button", { name: /go to page 2/i });
    fireEvent.click(nextBtn);

    await waitFor(() => {
      expect(screen.getByText(/Page 2/i)).toBeInTheDocument();
    });

    // Llamadas esperadas
    expect(mockGet).toHaveBeenCalledTimes(2);
    expect(mockGet.mock.calls[0][0]).toBe("/v1/locations"); // page 1
    expect(mockGet.mock.calls[1][0]).toBe("/v1/locations"); // page 2
  });
});
