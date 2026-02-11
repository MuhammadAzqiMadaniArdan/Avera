import { averaApi } from "@/lib/api/axiosClient";
import { ApiResponse } from "@/lib/api/response";
import { ReviewBase } from "./types";

interface param {
  page : number,
  rating: number
}

export async function getProductReviews(productId : string,param : param): Promise<
  ApiResponse<ReviewBase[]>
> {
  const response = await averaApi.get(`/api/v1/products/${productId}/reviews?page=${param.page}&rating=${param.rating}`);
  return response.data;
}

export async function submitReview(payload: {
  order_item_id: string;
  rating: number;
  comment: string;
}) {
  return averaApi.post("/api/v1/review", payload);
}
