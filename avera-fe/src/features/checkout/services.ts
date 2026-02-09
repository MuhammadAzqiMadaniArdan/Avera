import { averaApi } from "@/lib/api/axiosClient";
import { ApiResponse } from "@/lib/api/response";
import { Checkout, OrderCreated, UpdateCheckoutPayload } from "./types";

export async function getCheckout(): Promise<
  ApiResponse<Checkout>
> {
  const response = await averaApi.get("/api/v1/checkout");
  return response.data;
}

export async function updateCheckout(checkoutId : string,payload : UpdateCheckoutPayload): Promise<
  ApiResponse<Checkout>
> {
  const response = await averaApi.patch(`/api/v1/checkout/${checkoutId}`,payload);
  return response.data;
}

export async function orderCheckout(checkoutId : string): Promise<
  ApiResponse<OrderCreated>
> {
  const response = await averaApi.post(`/api/v1/checkout/${checkoutId}/place-order`);
  return response.data;
}

