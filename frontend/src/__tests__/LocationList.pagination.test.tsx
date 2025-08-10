import { render, screen, fireEvent, waitFor } from "@testing-library/react";
import LocationList from "@/components/LocationList";

jest.mock("@/lib/api", () => ({
  __esModule: true,
  listLocations: jest.fn(),
}));

import { listLocations } from "@/lib/api";

test("cambia de página y llama API con la nueva page", async () => {
  (listLocations as jest.Mock)
    .mockResolvedValueOnce({
      data: [{ id: 1, code: "A", name: "Uno", image: null }],
      meta: { current_page: 1, last_page: 2, per_page: 6, total: 7 },
    })
    .mockResolvedValueOnce({
      data: [{ id: 2, code: "B", name: "Dos", image: null }],
      meta: { current_page: 2, last_page: 2, per_page: 6, total: 7 },
    });

  render(<LocationList />);

  const page1 = await screen.findByRole("button", { name: /page 1/i });
  expect(page1).toHaveAttribute("aria-current", "page");

  const page2Btn = screen.getByRole("button", { name: /go to page 2/i });
  fireEvent.click(page2Btn);

  await waitFor(() => {
    expect(listLocations).toHaveBeenLastCalledWith(
      expect.objectContaining({ page: 2, per_page: 6 })
    );
  });
});
