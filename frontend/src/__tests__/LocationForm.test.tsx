import { render, screen, fireEvent, waitFor } from "@testing-library/react";
import LocationForm from "@/components/LocationForm";

jest.mock("@/lib/api", () => ({
  __esModule: true,
  createLocationFD: jest.fn(),
}));

import { createLocationFD } from "@/lib/api";

describe("LocationForm", () => {
  beforeEach(() => {
    (createLocationFD as jest.Mock).mockReset();
  });

  test("envía datos válidos y ejecuta callback onCreated", async () => {
    const onCreated = jest.fn();

    (createLocationFD as jest.Mock).mockResolvedValue({
      id: 1,
      code: "ABC",
      name: "Ciudad ABC",
      image: null,
    });

    render(<LocationForm onCreated={onCreated} />);

    fireEvent.change(screen.getByLabelText(/código/i), { target: { value: "ABC" } });
    fireEvent.change(screen.getByLabelText(/nombre/i), { target: { value: "Ciudad ABC" } });
    fireEvent.click(screen.getByRole("button", { name: /crear/i }));

    await waitFor(() => {
      expect(createLocationFD).toHaveBeenCalledWith(expect.any(FormData));
      expect(onCreated).toHaveBeenCalledWith(
        expect.objectContaining({ code: "ABC", name: "Ciudad ABC" })
      );
    });
  });
});
