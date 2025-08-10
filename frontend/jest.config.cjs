module.exports = {
  testEnvironment: 'jsdom',
  setupFilesAfterEnv: ['<rootDir>/src/test/setup.ts'],
  transform: {
    '^.+\\.(ts|tsx)$': ['ts-jest', { tsconfig: '<rootDir>/tsconfig.jest.json', isolatedModules: true }],
    '^.+\\.(js|jsx)$': 'babel-jest',
  },
  moduleNameMapper: {
    // si usas alias "@"
    '^@/(.*)$': '<rootDir>/src/$1',
  
    '\\.(css|less|scss|sass)$': '<rootDir>/src/test/styleStub.js',
  },
};
