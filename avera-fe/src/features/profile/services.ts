import { averaApi } from "@/lib/api/axiosClient";
import { ApiResponse } from "@/lib/api/response";
import { UserAddress, UserAddressCreatePayload, UserAddressUpdatePayload, UserProfile, UserProfileUpdatePayload } from "./types";

export async function getUserProfile(): Promise<
  ApiResponse<UserProfile>
> {
  const response = await averaApi.get(`/api/v1/user/profile`);
  return response.data;
}

export async function updateUserProfile(payload : UserProfileUpdatePayload): Promise<
  ApiResponse<UserProfile>
> {
  const response = await averaApi.patch('/api/v1/user/profile',payload);
  return response.data;
}

export async function uploadAvatar(image : File): Promise<
  ApiResponse<UserProfile>
> {
  const formData = new FormData();
  formData.append("avatar", image);

  const response = await averaApi.post(
    "/api/v1/user/profile/avatar",
    formData
  );
  return response.data;
}


export async function getUserAddresses(): Promise<
  ApiResponse<UserAddress[]>
> {
  const response = await averaApi.get(`/api/v1/user/address`);
  return response.data;
}

export async function createUserAddress(payload : UserAddressCreatePayload): Promise<
  ApiResponse<UserAddress>
> {
  const response = await averaApi.post('/api/v1/user/address',payload);
  return response.data;
}

export async function updateUserAddress(userAddressId : string,payload : UserAddressUpdatePayload): Promise<
  ApiResponse<UserAddress>
> {
  const response = await averaApi.patch(`/api/v1/user/address/${userAddressId}`,payload);
  return response.data;
}

export async function deleteUserAddress(userAddressId : string): Promise<
  ApiResponse<UserAddress>
> {
  const response = await averaApi.delete(`/api/v1/user/address/${userAddressId}`);
  return response.data;
}
