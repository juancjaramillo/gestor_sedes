// frontend/eslint.config.mjs
import js from "@eslint/js";
import globals from "globals";
import tseslint from "typescript-eslint";

export default [

  {
    ignores: [
      "node_modules",
      "dist",
      "build",
      "coverage",
      "*.local",
      "src/test/setup.js",
      "src/test/styleStub.js",
      "src/__tests__/**/*.js",
    ],
  },

  js.configs.recommended,
  ...tseslint.configs.recommended,

  // Reglas generales del proyecto
  {
    files: ["**/*.{js,ts,tsx}"],
    languageOptions: {
      ecmaVersion: 2022,
      sourceType: "module",
      globals: {
        ...globals.browser,
        ...globals.node,
      },
    },
    rules: {
      // Tus reglas opcionales aquí
    },
  },

  // Tests: habilitar globals de Jest solo dentro de tests
  {
    files: ["src/__tests__/**/*.{ts,tsx}", "src/test/**/*.{ts,tsx}"],
    languageOptions: {
      globals: {
        ...globals.jest,
        ...globals.browser,
        ...globals.node,
      },
    },
    rules: {},
  },

  // Configs en CJS
  {
    files: ["jest.config.cjs"],
    languageOptions: {
      sourceType: "commonjs",
      globals: { ...globals.node },
    },
    rules: {},
  },
];
