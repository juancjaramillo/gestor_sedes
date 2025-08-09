import { render, screen } from "@testing-library/react";
import LocationList from "../components/LocationList";

test("renders filters", () => {
  render(<LocationList />);
  expect(screen.getByLabelText(/Filter by name/i)).toBeInTheDocument();
  expect(screen.getByLabelText(/Filter by code/i)).toBeInTheDocument();
});
