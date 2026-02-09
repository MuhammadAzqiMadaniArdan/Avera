import { averaApi } from "@/lib/api/axiosClient";
import { ApiResponse } from "@/lib/api/response";
import { CourierSla } from "./type";


export async function getCourierSla(): Promise<
  ApiResponse<CourierSla[]>
> {
  const response = await averaApi.get(`/api/v1/courier/sla`);
  return response.data;
}

