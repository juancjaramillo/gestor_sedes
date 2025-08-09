import { render, screen, fireEvent, waitFor } from "@testing-library/react";

// Mock explícito: evita cargar ../lib/api.ts (que usa import.meta)
jest.mock("../lib/api", () => ({
  createLocationFD: jest.fn().mockResolvedValue({ data: {} }),
  updateLocationFD: jest.fn().mockResolvedValue({ data: {} }),
}));

import LocationForm from "../components/LocationForm";
import { createLocationFD } from "../lib/api";

describe("LocationForm", () => {
  it("envía FormData con archivo cuando se selecciona imagen", async () => {
    const onSuccess = jest.fn();

    render(<LocationForm onSuccess={onSuccess} />);

    fireEvent.change(screen.getByLabelText(/Code/i), { target: { value: "BOG" } });
    fireEvent.change(screen.getByLabelText(/Name/i), { target: { value: "Bogotá" } });

    const file = new File([new Uint8Array([1, 2, 3])], "img.png", { type: "image/png" });
    const input = screen.getByLabelText(/Imagen/i).parentElement!.querySelector("input[type=file]")!;
    fireEvent.change(input, { target: { files: [file] } });

    fireEvent.click(screen.getByRole("button", { name: /Crear/i }));

    await waitFor(() => expect(createLocationFD).toHaveBeenCalledTimes(1));
    expect(onSuccess).toHaveBeenCalled();
  });
});
