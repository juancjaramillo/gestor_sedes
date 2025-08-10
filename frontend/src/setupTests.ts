import "@testing-library/jest-dom";

// JSDOM no implementa createObjectURL/revokeObjectURL.
// Evitamos variables intermedias para no caer en eslint "no-unused-vars".
if (typeof URL.createObjectURL !== "function" || typeof URL.revokeObjectURL !== "function") {
  Object.assign(URL, {
    createObjectURL: URL.createObjectURL ?? (() => "blob:http://localhost/mock"),
    revokeObjectURL: URL.revokeObjectURL ?? (() => {}),
  });
}
