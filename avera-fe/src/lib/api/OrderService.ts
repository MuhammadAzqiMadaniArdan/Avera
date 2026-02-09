import { averaApi } from "./axiosClient";

export interface OrderResponse {
  id: string;
  productId: string;
  quantity: number;
  status: string;
}

export async function getOrders(): Promise<OrderResponse[]> {
  const response = await averaApi.get("/api/v1/orders");
  return response.data;
}
