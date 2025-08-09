import "@testing-library/jest-dom";

// Mock estable para jsdom (URL.createObjectURL / revokeObjectURL)
type URLWithObjectURL = typeof URL & {
  createObjectURL?: (obj: Blob) => string;
  revokeObjectURL?: (url: string) => void;
};

const URLMock = URL as URLWithObjectURL;

if (!URLMock.createObjectURL) {
  URLMock.createObjectURL = () => "blob:http://localhost/mock";
}
if (!URLMock.revokeObjectURL) {
  URLMock.revokeObjectURL = () => {}; 
}
