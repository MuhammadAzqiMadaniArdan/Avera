export const API_ENDPOINT = {
  AUTH: {
    LOGIN: "/api/v1/auth/login",
    ME: "/api/v1/auth/me",
  },

  PRODUCT: {
    SELLER_LIST: "/api/v1/seller/product",
    DETAIL: (id: string) => `/api/v1/products/${id}`,
  },

  ORDER: {
    USER_LIST: "/api/v1/orders",
  },
};
