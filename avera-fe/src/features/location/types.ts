export interface Address {
  id: number;
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

export interface Province {
    id: number;
    rajaongkir_id: number;
    name: string;
    cities?: City[];
}

export interface City {
    id : number;
    rajaongkir_id : number;
    province_id : number;
    name : string;
    province?: Province;
}

export interface District {
    id : number;
    rajaongkir_id : number;
    city_id : number;
    name : string;
    zip_code : string;
    city?: City;
}