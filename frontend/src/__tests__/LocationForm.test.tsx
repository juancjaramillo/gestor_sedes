import { render, screen, fireEvent, waitFor } from "@testing-library/react";
import LocationForm from "../components/LocationForm";

// Mock explícito de API (evita import.meta.env)
jest.mock("../lib/api", () => ({
  createLocationFD: jest.fn().mockResolvedValue({ data: {} }),
  updateLocationFD: jest.fn().mockResolvedValue({ data: {} }),
}));

import { createLocationFD } from "../lib/api";

describe("LocationForm", () => {
  it("envía FormData con archivo cuando se selecciona imagen", async () => {
    const onSuccess = jest.fn();
    render(<LocationForm onSuccess={onSuccess} />);

    fireEvent.change(screen.getByLabelText(/Code/i), {
      target: { value: "BOG" },
    });
    fireEvent.change(screen.getByLabelText(/Name/i), {
      target: { value: "Bogotá" },
    });

    const file = new File([new Uint8Array([1, 2, 3])], "img.png", {
      type: "image/png",
    });

    // Encuentra el <p> "Imagen (opcional)" y luego su input[file]
    const imagenText = screen.getByText(/Imagen \(opcional\)/i);
    const input = imagenText.parentElement!.querySelector(
      'input[type="file"]'
    ) as HTMLInputElement;

    fireEvent.change(input, { target: { files: [file] } });

    fireEvent.click(screen.getByRole("button", { name: /Crear/i }));

    await waitFor(() => expect(createLocationFD).toHaveBeenCalledTimes(1));
    expect(onSuccess).toHaveBeenCalled();
  });
});
