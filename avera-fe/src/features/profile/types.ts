export interface Address {
  id: string;
  label: "Home" | "Work";
  recipient_name: string;
  recipient_phone: string;
  street: string;
  village: string;
  district: string;
  province: string;
  city: string;
  province_id: number;
  city_id: number;
  default: boolean;
  other?: string;
}

export interface Region {
  id: string;
  name: string;
}

export interface UserAddress {
  id: string;
  label: "home" | "work";
  recipient_name: string;
  recipient_phone: string;
  
  province_name: string;
  city_name: string;
  district_name: string;
  village_name: string;

  province_id: number;
  city_id: number;
  district_id: number;
  village_id: number;
  
  postal_code?: string;
  address: string;
  other: string;

  is_default: boolean;
}

export interface UserAddressCreatePayload {
  label: "home" | "work";
  recipient_name: string;
  recipient_phone: string;
  
  province_name: string;
  city_name: string;
  district_name: string;
  village_name: string;

  province_id: number;
  city_id: number;
  district_id: number;
  village_id: number;
  
  postal_code?: string;
  address: string;
  other: string;

  is_default: boolean;
}

export interface UserAddressUpdatePayload {
  label?: "home" | "work";
  recipient_name?: string;
  recipient_phone?: string;
  
  province_name?: string;
  city_name?: string;
  district_name?: string;
  village_name?: string;

  province_id?: number;
  city_id?: number;
  district_id?: number;
  village_id?: number;
  
  postal_code?: string;
  address?: string;
  other?: string;

  is_default?: boolean;
}

// ========= user profile =========

export interface UserProfile {
  id: string;
  username: string;
  name: string;
  email: string;
  gender: "male" | "female" | "other";
  phone_number: string;
  avatar: string | null;
}

export interface UserProfileUpdatePayload {
  username?: string;
  name?: string;
  email?: string;
  gender?: "male" | "female" | "other";
  phone_number?: string;
  image?: File;
}

export interface uploadAvatarResponse {
  url: string;
}
export interface uploadAvatarPayload {
  image: File;
}