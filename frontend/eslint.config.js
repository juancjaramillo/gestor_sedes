import js from "@eslint/js";
import globals from "globals";
import tseslint from "typescript-eslint";
import react from "eslint-plugin-react";
import reactHooks from "eslint-plugin-react-hooks";

/** @type {import('eslint').Linter.FlatConfig[]} */
export default [
  // Ignorar artefactos
  { ignores: ["dist", "node_modules"] },

  // Base JS + TS
  js.configs.recommended,
  ...tseslint.configs.recommended,

  // Regla general para TS/TSX
  {
    files: ["**/*.{ts,tsx}"],
    languageOptions: {
      ecmaVersion: "latest",
      sourceType: "module",
      globals: { ...globals.browser, ...globals.node },
      parser: tseslint.parser,
    },
    plugins: {
      react,
      "react-hooks": reactHooks,
    },
    rules: {
      // React 17+ no necesita React en scope
      "react/react-in-jsx-scope": "off",
      "react-hooks/rules-of-hooks": "error",
      "react-hooks/exhaustive-deps": "warn",
      // Mantener estricto en src, aflojamos en tests con override abajo
      "@typescript-eslint/no-explicit-any": "error",
      "@typescript-eslint/no-require-imports": "error",
    },
    settings: {
      react: { version: "detect" },
    },
  },

  // Override para archivos CJS (como jest.config.cjs)
  {
    files: ["**/*.cjs"],
    languageOptions: {
      sourceType: "commonjs",
      globals: { ...globals.node },
    },
    rules: {
      // En CJS, module/require son válidos
      "no-undef": "off",
    },
  },

  // Override para tests: permitir "any" y require en setup utilitario si lo hay
  {
    files: ["src/__tests__/**/*.{ts,tsx}"],
    rules: {
      "@typescript-eslint/no-explicit-any": "off",
      "@typescript-eslint/no-require-imports": "off",
    },
  },
];
