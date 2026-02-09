let getTokenFn: (() => string | null) | null = null;

export const setTokenGetter = (fn: () => string | null) => {
  getTokenFn = fn;
};

export const getToken = () => getTokenFn?.() ?? null;
