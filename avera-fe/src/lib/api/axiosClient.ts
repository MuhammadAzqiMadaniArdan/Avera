import axios, { AxiosInstance } from "axios";
import { getAccessToken, setAccessToken } from "../auth/token";

// Axios instance untuk Identity Core (auth service)
export const authApi: AxiosInstance = axios.create({
  baseURL: process.env.NEXT_PUBLIC_AUTH_API_BASE_URL,
  withCredentials: true
});

// Axios instance untuk Avera BE (produk, order, dll)
export const averaApi: AxiosInstance = axios.create({
  baseURL: process.env.NEXT_PUBLIC_AVERA_API_BASE_URL,
  withCredentials: true,
});

// Attach access token ke request
const attachAuthInterceptor = (instance: AxiosInstance) => {
  instance.interceptors.request.use((config) => {
    const token = getAccessToken();
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  });
};

attachAuthInterceptor(authApi);
attachAuthInterceptor(averaApi);

// Queue untuk handle refresh token
let isRefreshing = false;
let failedQueue: {
  resolve: (token: string) => void;
  reject: (error: unknown) => void;
}[] = [];

const processQueue = (error: unknown, token: string | null = null) => {
  failedQueue.forEach((prom) => {
    if (error) prom.reject(error);
    else prom.resolve(token!);
  });
  failedQueue = [];
};

// Interceptor untuk refresh token
averaApi.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config;

    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true;

      if (isRefreshing) {
        return new Promise((resolve, reject) => {
          if (failedQueue.length > 20) {
            reject("Too many pending requests");
            return;
          }
          failedQueue.push({
            resolve: (token: string) => {
              originalRequest.headers.Authorization = `Bearer ${token}`;
              resolve(averaApi(originalRequest));
            },
            reject,
          });
        });
      }

      isRefreshing = true;

      try {
        const refreshResponse = await authApi.post(
          "/api/v1/oauth/token",
          {},
          { headers: { "Content-Type": "application/x-www-form-urlencoded" } },
        );

        const newAccessToken = refreshResponse.data.access_token;
        setAccessToken(newAccessToken);

        processQueue(null, newAccessToken);

        originalRequest.headers.Authorization = `Bearer ${newAccessToken}`;
        return averaApi(originalRequest);
      } catch (err) {
        processQueue(err, null);
        setAccessToken(null);
        return Promise.reject(err);
      } finally {
        isRefreshing = false;
      }
    }

    return Promise.reject(error);
  },
);
