import { averaApi } from "@/lib/api/axiosClient";
import { ApiResponse } from "@/lib/api/response";
import { ProductBase, ProductCreatePayload, ProductDetail, ProductSellerIndex } from "./types";

interface SearchParams {
  keyword?: string,
  page?: number,
  sort?: string,
  order?: string,
}

export async function getProductSearch(filter: SearchParams = {}): Promise<ApiResponse<ProductBase[]>> {
  const { keyword, page = 1, sort, order } = filter;

  const response = await averaApi.get("/api/v1/products", {
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
  ApiResponse<ProductBase[]>
> {
  const response = await averaApi.get("/api/v1/products/random");
  return response.data;
}

export async function getProductTop(): Promise<
  ApiResponse<ProductBase[]>
> {
  const response = await averaApi.get("/api/v1/products/top");
  return response.data;
}

export async function getProductStore(storeSlug : string): Promise<
  ApiResponse<ProductBase[]>
> {
  const response = await averaApi.get(`/api/v1/products/${storeSlug}/store`);
  return response.data;
}

export async function getProductCategory(categorySlug : string): Promise<
  ApiResponse<ProductBase[]>
> {
  const response = await averaApi.get(`/api/v1/products/${categorySlug}/category`);
  return response.data;
}

// // Ambil detail product
export async function getProductSeller(): Promise<ApiResponse<ProductSellerIndex[]>
> {
  const response = await averaApi.get(`/api/v1/seller/products`);
  return response.data;
}


interface CreateProductForm {
  name: string;
  description: string;
  categoryId: string;
}

export async function createProduct(data: CreateProductForm): Promise<ApiResponse<ProductBase>
> {
  const payload: ProductCreatePayload = {
    name: data.name,
    description: data.description,
    category_id: data.categoryId, 
  };
  const response = await averaApi.post("/api/v1/seller/products", payload);
  return response.data;
}

export async function createProductImage(data: CreateProductForm): Promise<ApiResponse<ProductBase>
> {
  const payload: ProductCreatePayload = {
    name: data.name,
    description: data.description,
    category_id: data.categoryId, 
  };
  const response = await averaApi.post("/api/v1/seller/products", payload);
  return response.data;
}

export async function getProductByCompound(compound: string): Promise<ApiResponse<ProductDetail>> {
  const response = await averaApi.get(`/api/v1/products/${compound}`);
  return response.data;
}

