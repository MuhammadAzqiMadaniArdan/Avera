import { averaApi } from "@/lib/api/axiosClient";
import { ApiResponse } from "@/lib/api/response";
import { CategoryBase, CategoryTree } from "./types";

export async function getCategoryParent(): Promise<
  ApiResponse<CategoryBase[]>
> {
  const response = await averaApi.get("/api/v1/category");
  return response.data;
}

export async function getCategoryTree(): Promise<
  ApiResponse<CategoryTree[]>
> {
  const response = await averaApi.get("/api/v1/category/tree");
  return response.data;
}
