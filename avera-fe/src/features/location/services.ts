import { averaApi } from "@/lib/api/axiosClient";
import { ApiResponse } from "@/lib/api/response";
import { City, District, Province } from "./types";


export async function getProvinces(): Promise<
  ApiResponse<Province[]>
> {
  const response = await averaApi.get(`/api/v1/provinces`);
  return response.data;
}

export async function getCities(): Promise<
  ApiResponse<City[]>
> {
  const response = await averaApi.get(`/api/v1/cities`);
  return response.data;
}

export async function getCityProvince(provinceId : number): Promise<
  ApiResponse<City[]>
> {
  const response = await averaApi.get(`/api/v1/cities/${provinceId}`);
  return response.data;
}

export async function getDistrictCity(cityId : number): Promise<
  ApiResponse<District[]>
> {
  const response = await averaApi.get(`/api/v1/districts/${cityId}`);
  return response.data;
}