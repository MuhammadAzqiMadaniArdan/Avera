import { averaApi } from "@/lib/api/axiosClient";
import { ApiResponse } from "@/lib/api/response";
import { Category, CategoryHomepage } from "./types";

export async function getCategoryParent(): Promise<
  ApiResponse<CategoryHomepage[]>
> {
  const response = await averaApi.get("/api/v1/category");
  return response.data;
}

export async function getCategoryTree(): Promise<
  ApiResponse<Category[]>
> {
  const response = await averaApi.get("/api/v1/category/tree");
  return response.data;
}

// // Buat product baru
// export async function createProduct(payload: ProductCreatePayload): Promise<ProductResponse> {
//   const response = await averaApi.post("/api/v1/products", payload);
//   return response.data;
// }

// // Ambil detail product
// export async function getProductById(productId: string): Promise<ProductResponse> {
//   const response = await averaApi.get(`/api/v1/products/${productId}`);
//   return response.data;
// }
// // Ambil detail product
// export async function getProductByCompound(compound: string): Promise<ProductResponse> {
//   const response = await averaApi.get(`/api/v1/product/${compound}`);
//   return response.data;
// }

// // Ambil detail product
// export async function getProductSeller(): Promise<ProductResponse> {
//   const response = await averaApi.get(`/api/v1/seller/product`);
//   return response.data;
// }

// // Update product
// export async function updateProduct(productId: string, payload: Partial<ProductCreatePayload>): Promise<ProductResponse> {
//   const response = await averaApi.put(`/api/v1/products/${productId}`, payload);
//   return response.data;
// }
