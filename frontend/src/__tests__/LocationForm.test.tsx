import { render, screen, fireEvent, waitFor } from "@testing-library/react";
import LocationForm from "../components/LocationForm";

jest.mock("../lib/api", () => ({
  __esModule: true,
  default: {
    post: jest.fn().mockResolvedValue({ data: { id: 99, code: "NEW", name: "Nueva" } }),
  },
}));

const api = require("../lib/api").default;

describe("LocationForm", () => {
  test("muestra errores de validación cuando faltan campos", async () => {
    const onCreated = jest.fn();
    render(<LocationForm onCreated={onCreated} />);

    fireEvent.click(screen.getByRole("button", { name: /create/i }));

    await waitFor(() => {
      // Mensajes reales que muestra tu UI (MUI + RHF)
      expect(screen.getByText(/code is required/i)).toBeInTheDocument();
      expect(screen.getByText(/name is required/i)).toBeInTheDocument();
    });

    expect(onCreated).not.toHaveBeenCalled();
  });

  test("envía formulario válido y llama onCreated", async () => {
    const onCreated = jest.fn();
    render(<LocationForm onCreated={onCreated} />);

    fireEvent.change(screen.getByLabelText(/code/i), { target: { value: "ABC" } });
    fireEvent.change(screen.getByLabelText(/name/i), { target: { value: "Ciudad ABC" } });
    fireEvent.change(screen.getByLabelText(/image url/i), { target: { value: "" } });

    fireEvent.click(screen.getByRole("button", { name: /create/i }));

    await waitFor(() => {
      expect(api.post).toHaveBeenCalledWith("/v1/locations", { code: "ABC", name: "Ciudad ABC", image: undefined });
      expect(onCreated).toHaveBeenCalled();
    });
  });
});
