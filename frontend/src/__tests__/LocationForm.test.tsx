import { render, screen, fireEvent, waitFor } from "@testing-library/react";
import LocationForm from "../components/LocationForm";
import * as api from "../lib/api";

describe("LocationForm", () => {
  it("envía FormData con archivo cuando se selecciona imagen", async () => {
    const spyCreate = jest .spyOn(api, "createLocationFD").mockResolvedValue({ data: {} });
    const onSuccess = jest .fn();

    render(<LocationForm onSuccess={onSuccess} />);

    fireEvent.change(screen.getByLabelText(/Code/i), { target: { value: "BOG" } });
    fireEvent.change(screen.getByLabelText(/Name/i), { target: { value: "Bogotá" } });

    const file = new File([new Uint8Array([1,2,3])], "img.png", { type: "image/png" });
    const input = screen.getByLabelText(/Imagen/i).parentElement!.querySelector("input[type=file]")!;
    fireEvent.change(input, { target: { files: [file] } });

    fireEvent.click(screen.getByRole("button", { name: /Crear/i }));

    await waitFor(() => expect(spyCreate).toHaveBeenCalledTimes(1));
    expect(onSuccess).toHaveBeenCalled();
  });
});
