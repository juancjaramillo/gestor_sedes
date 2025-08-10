declare module "fast-text-encoding" {
  export class TextEncoder {
    encode(input?: string): Uint8Array;
  }

  export class TextDecoder {
    constructor(label?: string, options?: { fatal?: boolean; ignoreBOM?: boolean });
    decode(
      input?: ArrayBufferView | ArrayBuffer | null,
      options?: { stream?: boolean }
    ): string;
  }
}
