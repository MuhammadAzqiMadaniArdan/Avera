import { averaApi } from "@/lib/api/axiosClient";
import { ApiResponse } from "@/lib/api/response";

export interface StoreCreatePayload {
  name: string;
  description?: string;
}

export interface UserResource {
  id: string;
  name: string;
}

export interface StoreResponse {
  user_id: string;
  name: string;
  slug: string;
  description: string;
  banner_url?: string;
  rating: number;
  verified: boolean;
  user?: UserResource;
}


export interface StoreCreatePayload {
  name: string;
  description?: string;
}

export interface StoreResponse {
  user_id: string;
  name: string;
  slug: string;
  description: string;
  banner_url?: string;
  rating: number;
  verified: boolean;
}

// Buat product baru
export async function createStore(
  payload: StoreCreatePayload
): Promise<ApiResponse<StoreResponse>> {
  const response = await averaApi.post("/api/v1/store/", payload);
  return response.data;
}


// Ambil detail product
export async function getStoreById(productId: string): Promise<StoreResponse> {
  const response = await averaApi.get(`/api/v1/store/${productId}`);
  return response.data;
}

// Ambil detail product
export async function getStoreBySeller(): Promise<ApiResponse<StoreResponse>> {
  const response = await averaApi.get(`/api/v1/seller/store/`);
  return response.data;
}

// Update product
export async function updateStore(productId: string, payload: Partial<StoreCreatePayload>): Promise<StoreResponse> {
  const response = await averaApi.put(`/api/v1/store/${productId}`, payload);
  return response.data;
}