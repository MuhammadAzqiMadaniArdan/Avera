import axios, { AxiosInstance } from "axios";
import { getAccessToken, setAccessToken } from "@/lib/auth/token";

export const api = axios.create({
  baseURL: "http://localhost:8000",
  withCredentials: true,
});

export const averaApi = axios.create({
  baseURL: "https://282e956eb45e.ngrok-free.app",
    withCredentials: true,

});
const attachAuth = (axiosInstance: AxiosInstance) => {
  axiosInstance.interceptors.request.use((config) => {
    const token = getAccessToken();
    console.log('token',token)
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  });
};
attachAuth(api);
attachAuth(averaApi);

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

averaApi.interceptors.response.use(
  (res) => res,
  async (error) => {
    const original = error.config;

    if (error.response?.status === 401 && !original._retry) {
      original._retry = true;

      if (isRefreshing) {
        return new Promise((resolve, reject) => {
          if (failedQueue.length > 20) {
            reject("Too many pending request");
            return;
          }
          failedQueue.push({
            resolve: (token: string) => {
              original.headers.Authorization = `Bearer ${token}`;
              resolve(api(original));
            },
            reject,
          });
        });
      }

      isRefreshing = true;

      try {
        const resPromise = 
          api.post(
            "/api/v1/oauth/token",
            {},
            {
              headers: {
                "Content-Type": "application/x-www-form-urlencoded",
              },
            }
          )

        const timeoutPromise = 
          new Promise((_, reject) =>
            setTimeout(() => reject(new Error("Refresh timeout")), 5000)
          )

        const res = await Promise.race([
          resPromise,
          timeoutPromise
        ]) as Awaited<typeof resPromise>;

        console.log("ini", res.data);

        const newAccessToken = res.data.access_token;
        setAccessToken(newAccessToken);

        // Retry semua request di queue
        processQueue(null, newAccessToken);

        // Retry original request
        original.headers.Authorization = `Bearer ${newAccessToken}`;
        return api(original);
      } catch (err) {
        processQueue(err, null);
        setAccessToken(null);
        return Promise.reject(err);
      } finally {
        isRefreshing = false;
      }
    }

    return Promise.reject(error);
  }
);
console.log(getAccessToken());
