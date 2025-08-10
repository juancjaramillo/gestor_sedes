import '@testing-library/jest-dom';

if (!('IS_REACT_ACT_ENVIRONMENT' in globalThis)) {
  Object.defineProperty(globalThis, 'IS_REACT_ACT_ENVIRONMENT', {
    value: true,
    writable: true,
    configurable: true,
  });
}

if (!Element.prototype.scrollIntoView) {
  Element.prototype.scrollIntoView = () => {};
}

const realError = console.error;
let errorSpy: jest.SpyInstance;

beforeAll(() => {
  errorSpy = jest.spyOn(console, 'error').mockImplementation((...args: unknown[]) => {
    const msg = String(args[0] ?? '');
    if (/not wrapped in act/i.test(msg)) return; 
    (realError as (...a: unknown[]) => void)(...args);
  });
});

afterAll(() => {
  errorSpy?.mockRestore();
});
