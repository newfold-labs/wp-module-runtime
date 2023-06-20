export interface DefaultSdk {
  wpversion: string;
}

interface RuntimeSdk {
  hasCapability: (
    name: "hasEcomdash" | "hasYithExtended" | "isEcommerce" | "isJarvis"
  ) => boolean;
  adminUrl: (path: string) => string;
  createApiUrl: (path: string, qs?: Record<string, any>) => string;
  siteDetails: { url: string; title: string };
  sdk: DefaultSdk;
}

export const NewfoldRuntime: RuntimeSdk;
