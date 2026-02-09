import { averaApi } from "@/lib/api/axiosClient";
import { ApiResponse } from "@/lib/api/response";
import { ProductBase, ProductCreatePayload, ProductDetail, ProductHomepage, ProductSellerIndex } from "./types";

interface SearchParams {
  keyword?: string,
  page?: number,
  sort?: string,
  order?: string,
}

export async function getProductSearch(filter: SearchParams = {}): Promise<ApiResponse<ProductHomepage[]>> {
  const { keyword, page = 1, sort, order } = filter;

  const response = await averaApi.get("/api/v1/product", {
    params: {
      keyword,
      page,
      sort,
      order,
    },
  });

  return response.data;
}

export async function getRandomProduct(): Promise<
  ApiResponse<ProductHomepage[]>
> {
  const response = await averaApi.get("/api/v1/product/random");
  return response.data;
}

export async function getProductTop(): Promise<
  ApiResponse<ProductHomepage[]>
> {
  const response = await averaApi.get("/api/v1/product/top");
  return response.data;
}

export async function getProductStore(storeSlug : string): Promise<
  ApiResponse<ProductHomepage[]>
> {
  const response = await averaApi.get(`/api/v1/product/${storeSlug}/store`);
  return response.data;
}

export async function getProductCategory(categorySlug : string): Promise<
  ApiResponse<ProductHomepage[]>
> {
  const response = await averaApi.get(`/api/v1/product/${categorySlug}/category`);
  return response.data;
}

// // Ambil detail product
export async function getProductSeller(): Promise<ApiResponse<ProductSellerIndex[]>
> {
  const response = await averaApi.get(`/api/v1/seller/product`);
  return response.data;
}


interface CreateProductForm {
  name: string;
  description: string;
  categoryId: string;
}

interface CreateProductImageForm {
  name: string;
  description: string;
  categoryId: string;
}
// // Buat product baru
export async function createProduct(data: CreateProductForm): Promise<ApiResponse<ProductBase>
> {
  const payload: ProductCreatePayload = {
    name: data.name,
    description: data.description,
    category_id: data.categoryId, // 🔥 mapping DI SINI
  };
  const response = await averaApi.post("/api/v1/seller/product", payload);
  return response.data;
}

export async function createProductImage(data: CreateProductForm): Promise<ApiResponse<ProductBase>
> {
  const payload: ProductCreatePayload = {
    name: data.name,
    description: data.description,
    category_id: data.categoryId, // 🔥 mapping DI SINI
  };
  const response = await averaApi.post("/api/v1/seller/product", payload);
  return response.data;
}

// export async function getProductByCompound(compound: string): Promise<ApiResponse<Product>> {
//   const response = await averaApi.get(`/api/v1/product/${compound}`);
//   return response.data;
// }

export async function getProductByCompound(compound: string): Promise<ApiResponse<ProductDetail>> {
  const response = await averaApi.get(`/api/v1/product/${compound}`);
  return response.data;
}

// // Ambil detail product
// export async function getProductById(productId: string): Promise<ProductResponse> {
//   const response = await averaApi.get(`/api/v1/products/${productId}`);
//   return response.data;
// }
// // Ambil detail product



// // Update product
// export async function updateProduct(productId: string, payload: Partial<ProductCreatePayload>): Promise<ProductResponse> {
//   const response = await averaApi.put(`/api/v1/products/${productId}`, payload);
//   return response.data;
// }
