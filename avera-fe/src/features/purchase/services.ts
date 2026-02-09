import { averaApi } from "@/lib/api/axiosClient";
import { ApiResponse } from "@/lib/api/response";
import { OrderItem, UserPurchase } from "./types";

// interface param {
//   page : number,
//   rating: number
// }

export async function getUserPurchase(): Promise<
  ApiResponse<UserPurchase[]>
> {
  const response = await averaApi.get(`/api/v1/order`);
  return response.data;
}

export async function getOrderDetail(id: string): Promise<ApiResponse<UserPurchase>> {
  const res = await averaApi.get(`/api/v1/order/${id}`);
  return res.data;
}

export async function getOrderItemDetail(id: string): Promise<ApiResponse<OrderItem>> {
  const res = await averaApi.get(`/api/v1/order/item/${id}`);
  return res.data;
}
