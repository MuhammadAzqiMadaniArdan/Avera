"use client";

import { getCityProvince, getDistrictCity, getProvinces } from "@/features/location/services";
import { City, District, Province } from "@/features/location/types";
import { notify } from "@/lib/toast/notify";
import React, { useEffect, useState } from "react";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

type Village = { id: number; name: string; postal_code: string };

interface Props {
  province: number | null;
  setProvinceId: (v: number | null) => void;
  setProvinceName: (name: string) => void;
  city: number | null;
  setCityId: (v: number | null) => void;
  setCityName: (name: string) => void;
  district: number | null;
  setDistrictId: (v: number | null) => void;
  setDistrictName: (name: string) => void;
  village: number | null;
  setVillageId: (v: number | null) => void;
  setVillageName: (name: string) => void;
  postalCode: string;
  setPostalCode: (v: string) => void;
}

export default function AddressRegionSelect({
  province,
  setProvinceId,
  setProvinceName,
  city,
  setCityId,
  setCityName,
  district,
  setDistrictId,
  setDistrictName,
  village,
  setVillageId,
  setVillageName,
  postalCode,
  setPostalCode,
}: Props) {
  const [provinces, setProvinces] = useState<Province[]>([]);
  const [cities, setCities] = useState<City[]>([]);
  const [districts, setDistricts] = useState<District[]>([]);
  const [villages, setVillages] = useState<Village[]>([]);
  const [loadingProv, setLoadingProv] = useState(true);
  const [loadingCity, setLoadingCity] = useState(false);
  const [loadingDistrict, setLoadingDistrict] = useState(false);

  // Step tracking
  const [step, setStep] = useState(0);

  // Initialize step based on existing values
  useEffect(() => {
    if (province && city && district && village) setStep(4);
    else if (province && city && district) setStep(3);
    else if (province && city) setStep(2);
    else if (province) setStep(1);
    else setStep(0);
  }, [province, city, district, village]);

  // Load provinces
  useEffect(() => {
    const fetchProvinces = async () => {
      setLoadingProv(true);
      try {
        const res = await getProvinces();
        setProvinces(res.data);
      } catch (err) {
        notify.error("Gagal mengambil provinces");
      } finally {
        setLoadingProv(false);
      }
    };
    fetchProvinces();
  }, []);

  // Load cities if province exists
  useEffect(() => {
    if (!province) {
      setCities([]);
      setCityId(null);
      setCityName("");
      return;
    }

    const fetchCities = async () => {
      setLoadingCity(true);
      try {
        const res = await getCityProvince(province);
        setCities(res.data);
        if (city) {
          const selectedCity = res.data.find((c) => c.id === city);
          if (selectedCity) setCityName(selectedCity.name);
        }
      } catch (err) {
        notify.error("Gagal mengambil city");
      } finally {
        setLoadingCity(false);
      }
    };
    fetchCities();
  }, [province]);

  // Districts dummy
  useEffect(() => {
    if (!city) return;
     const fetchDistricts = async () => {
      setLoadingDistrict(true);
      try {
        const res = await getDistrictCity(city);
        setDistricts(res.data);
        if (district) {
          const selectedDistrict = res.data.find((d) => d.id === district);
          if (selectedDistrict) setDistrictName(selectedDistrict.name);
        }
      } catch (err) {
        notify.error("Gagal mengambil District");
      } finally {
        setLoadingDistrict(false);
      }
    };
    fetchDistricts()
  }, [city]);

  // Villages dummy
  useEffect(() => {
    if (!district) return;
    const dummyVillages: Village[] = [
      { id: 1, name: "Kelurahan Alpha", postal_code: "12345" },
      { id: 2, name: "Kelurahan Beta", postal_code: "12346" },
    ];
    setVillages(dummyVillages);
    if (village) {
      const selected = dummyVillages.find((v) => v.id === village);
      if (selected) {
        setVillageName(selected.name);
        setPostalCode(selected.postal_code);
      }
    }
  }, [district]);

  const disabledClass = "cursor-not-allowed opacity-50";

  return (
    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
      {/* Province */}
      <div>
        <Label>Province</Label>
        <Select
          value={province !== null ? String(province) : ""}
          onValueChange={(val) => {
            const id = Number(val);
            const name = provinces.find((p) => p.id === id)?.name ?? "";
            setProvinceId(id);
            setProvinceName(name);
            setCityId(null);
            setCityName("");
            setDistrictId(null);
            setDistrictName("");
            setVillageId(null);
            setVillageName("");
            setPostalCode("");
            setStep(1);
          }}
          disabled={loadingProv}
        >
          <SelectTrigger className={`bg-white text-black ${loadingProv ? disabledClass : ""}`}>
            <SelectValue placeholder={loadingProv ? "Loading provinces..." : "Select Province"} />
          </SelectTrigger>
          <SelectContent className="bg-white text-black">
            {provinces.map((p) => (
              <SelectItem key={p.id} value={String(p.id)} className="text-black">{p.name}</SelectItem>
            ))}
          </SelectContent>
        </Select>
      </div>

      {/* City */}
      <div>
        <Label>City / Kabupaten</Label>
        <Select
          value={city !== null ? String(city) : ""}
          onValueChange={(val) => {
            const id = Number(val);
            const selected = cities.find((c) => c.id === id);
            setCityId(selected?.id ?? null);
            setCityName(selected?.name ?? "");
            setDistrictId(null);
            setDistrictName("");
            setVillageId(null);
            setVillageName("");
            setPostalCode("");
            setStep(2);
          }}
          disabled={step < 1 || loadingCity}
        >
          <SelectTrigger className={`bg-white text-black ${(step < 1 || loadingCity) ? disabledClass : ""}`}>
            <SelectValue placeholder={loadingCity ? "Loading cities..." : "Select City"} />
          </SelectTrigger>
          <SelectContent className="bg-white text-black">
            {cities.map((c) => (
              <SelectItem key={c.id} value={String(c.id)} className="text-black">{c.name}</SelectItem>
            ))}
          </SelectContent>
        </Select>
      </div>

      {/* District */}
      <div>
        <Label>District</Label>
        <Select
          value={district !== null ? String(district) : ""}
          onValueChange={(val) => {
            const id = Number(val);
            const found = districts.find((d) => d.id === id);
            setDistrictId(found?.id ?? null);
            setDistrictName(found?.name ?? "");
            setVillageId(null);
            setVillageName("");
            setPostalCode("");
            setStep(3);
          }}
          disabled={step < 2}
        >
           <SelectTrigger className={`bg-white text-black ${(step < 2 || loadingDistrict) ? disabledClass : ""}`}>
            <SelectValue placeholder={loadingDistrict ? "Loading districts..." : "Select District"} />
          </SelectTrigger>
          <SelectContent className="bg-white text-black">
            {districts.map((d) => (
              <SelectItem key={d.id} value={String(d.id)} className="text-black">{d.name}</SelectItem>
            ))}
          </SelectContent>
        </Select>
      </div>

      {/* Village */}
      <div>
        <Label>Village</Label>
        <Select
          value={village !== null ? String(village) : ""}
          onValueChange={(val) => {
            const id = Number(val);
            const found = villages.find((v) => v.id === id);
            setVillageId(found?.id ?? null);
            setVillageName(found?.name ?? "");
            setPostalCode(found?.postal_code ?? "");
            setStep(4);
          }}
          disabled={step < 3}
        >
          <SelectTrigger className={`bg-white text-black ${step < 3 ? disabledClass : ""}`}>
            <SelectValue placeholder="Select Village" />
          </SelectTrigger>
          <SelectContent className="bg-white text-black">
            {villages.map((v) => (
              <SelectItem key={v.id} value={String(v.id)} className="text-black">{v.name}</SelectItem>
            ))}
          </SelectContent>
        </Select>
      </div>

      {/* Postal Code */}
      <div>
        <Label>Postal Code</Label>
        <Input value={postalCode} disabled className="bg-gray-100" />
      </div>
    </div>
  );
}
