import "@testing-library/jest-dom";

// Ampliamos tipos del constructor URL para evitar castings/ts-ignore
declare global {
  interface URLConstructor {
    createObjectURL?: (obj: unknown) => string;
    revokeObjectURL?: (url: string) => void;
  }
}

// Mock en jsdom cuando no existe
if (typeof URL.createObjectURL !== "function") {
  URL.createObjectURL = () => "blob:http://localhost/mock";
}
if (typeof URL.revokeObjectURL !== "function") {
  URL.revokeObjectURL = () => {};
}
