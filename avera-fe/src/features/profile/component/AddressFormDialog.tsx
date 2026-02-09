"use client";

import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useState, useEffect } from "react";
import AddressRegionSelect from "./AddressRegionalSelect";
import { UserAddress } from "../types";

interface Props {
  open: boolean;
  onClose: () => void;
  initial?: UserAddress | null;
  onSubmit: (data: UserAddress) => Promise<void>;
}

export function AddressFormDialog({ open, onClose, initial, onSubmit }: Props) {
  // ====== STATE FORM ======
  const [label, setLabel] = useState<"home" | "work">("home");
  const [recipientName, setRecipientName] = useState("");
  const [recipientPhone, setRecipientPhone] = useState("");
  const [address, setAddress] = useState("");
  const [other, setOther] = useState("");
  const [isDefault, setIsDefault] = useState(false);

  const [provinceId, setProvinceId] = useState<number | null>(null);
  const [provinceName, setProvinceName] = useState("");
  const [cityId, setCityId] = useState<number | null>(null);
  const [cityName, setCityName] = useState("");
  const [districtId, setDistrictId] = useState<number | null>(null);
  const [districtName, setDistrictName] = useState("");
  const [villageId, setVillageId] = useState<number | null>(null);
  const [villageName, setVillageName] = useState("");
  const [postalCode, setPostalCode] = useState("");

  const [submitting, setSubmitting] = useState(false);

  // ====== SYNC STATE DENGAN EDITING ======
  useEffect(() => {
    setLabel(initial?.label ?? "home");
    setRecipientName(initial?.recipient_name ?? "");
    setRecipientPhone(initial?.recipient_phone ?? "");
    setAddress(initial?.address ?? "");
    setOther(initial?.other ?? "");
    setIsDefault(initial?.is_default ?? false);

    setProvinceId(initial?.province_id ?? null);
    setProvinceName(initial?.province_name ?? "");
    setCityId(initial?.city_id ?? null);
    setCityName(initial?.city_name ?? "");
    setDistrictId(initial?.district_id ?? null);
    setDistrictName(initial?.district_name ?? "");
    setVillageId(initial?.village_id ?? null);
    setVillageName(initial?.village_name ?? "");
    setPostalCode(initial?.postal_code ?? "");
  }, [initial]);

  // ====== VALIDASI FORM ======
  const isValid =
    ["home", "work"].includes(label) &&
    recipientName.trim().length >= 3 &&
    recipientName.trim().length <= 255 &&
    /^\p{L}[\p{L}\p{N}\s\-]*$/u.test(recipientName) &&
    /^(\+62|62|0)8[1-9][0-9]{6,10}$/.test(recipientPhone) &&
    provinceId &&
    provinceName &&
    cityId &&
    cityName &&
    districtName.trim().length >= 3 &&
    districtName.trim().length <= 255 &&
    villageId &&
    villageName &&
    address.trim().length >= 10 &&
    address.trim().length <= 1000 &&
    (!other || (other.trim().length >= 10 && other.trim().length <= 255));

  const submit = () => {
    if (!isValid) return;
    try {
      setSubmitting(true);

      onSubmit({
        id: initial?.id ?? null,
        label,
        recipient_name: recipientName,
        recipient_phone: recipientPhone,
        province_name: provinceName,
        city_name: cityName,
        district_name: districtName,
        village_name: villageName,
        postal_code: postalCode,
        address,
        other,
        province_id: provinceId!,
        city_id: cityId!,
        district_id: districtId!,
        village_id: villageId!,
        is_default: isDefault,
      });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent
        className="max-h-[85vh] w-full sm:max-w-lg flex flex-col"
        style={{ maxHeight: "85vh" }}
      >
        <DialogHeader>
          <DialogTitle>{initial ? "Edit Address" : "New Address"}</DialogTitle>
        </DialogHeader>

        <div className="flex-1 space-y-3 overflow-y-auto no-scrollbar">
          {/* Label */}
          <div>
            <Label>Label</Label>
            <div className="flex space-x-3 mt-2">
              {["home", "work"].map((l) => (
                <button
                  key={l}
                  type="button"
                  onClick={() => setLabel(l as "home" | "work")}
                  className={`flex-1 border rounded p-3 text-center cursor-pointer transition ${
                    label === l
                      ? "bg-primary text-white border-green-600"
                      : "bg-white text-gray-800 border-gray-300"
                  } hover:bg-green-100`}
                >
                  {l.charAt(0).toUpperCase() + l.slice(1)}
                </button>
              ))}
            </div>
          </div>

          {/* Recipient Name */}
          <div>
            <Label>Recipient Name</Label>
            <Input
              type="text"
              placeholder="Full Name"
              value={recipientName}
              maxLength={255}
              onChange={(e) => setRecipientName(e.target.value)}
            />
          </div>

          {/* Recipient Phone */}
          <div>
            <Label>Recipient Phone</Label>
            <Input
              type="tel"
              placeholder="0812xxxx"
              value={recipientPhone}
              maxLength={16}
              pattern="^(\+62|62|0)8[1-9][0-9]{6,10}$"
              onChange={(e) => setRecipientPhone(e.target.value)}
            />
          </div>

          {/* Address */}
          <div>
            <Label>Address</Label>
            <textarea
              rows={3}
              placeholder="Street, Building, etc."
              value={address}
              maxLength={1000}
              className="w-full border rounded px-3 py-2 resize-none"
              onChange={(e) => setAddress(e.target.value)}
            />
          </div>

          {/* Other */}
          <div>
            <Label>Other (Optional)</Label>
            <Input
              type="text"
              placeholder="Landmark / Notes"
              value={other}
              maxLength={255}
              onChange={(e) => setOther(e.target.value)}
            />
          </div>

          {/* Region */}
          <AddressRegionSelect
            province={provinceId}
            setProvinceId={setProvinceId}
            city={cityId}
            setCityId={setCityId}
            district={districtId}
            setDistrictId={setDistrictId}
            village={villageId}
            setVillageId={setVillageId}
            postalCode={postalCode}
            setPostalCode={setPostalCode}
            setProvinceName={setProvinceName}
            setCityName={setCityName}
            setDistrictName={setDistrictName}
            setVillageName={setVillageName}
          />

          {/* Default */}
          <div className="flex items-center space-x-2">
            <input
              type="checkbox"
              checked={isDefault}
              onChange={(e) => setIsDefault(e.target.checked)}
            />
            <Label>Set as default</Label>
          </div>
        </div>

        <DialogFooter>
          <Button variant="outline" onClick={onClose}>
            Cancel
          </Button>
          <Button onClick={submit} disabled={!isValid || submitting}>
            {submitting ? "Saving..." : "Save"}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
