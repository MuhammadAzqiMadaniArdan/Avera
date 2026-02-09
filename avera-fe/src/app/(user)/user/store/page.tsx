"use client";
import { StoreCreatedForm } from "@/features/store/components/StoreCreatedForm";
import { Button } from "@/components/ui/button";
import React, { useEffect, useState } from "react";
import { getAccessToken } from "@/lib/auth/token";
import { createStore, getStoreBySeller } from "@/features/store/services";

export default function StorePage() {
  const [name, setName] = useState("");
  const [description, setDescription] = useState("");
  const [isStore, setIsStore] = useState(false);
  // ======================
  // Form validasi
  // ======================
  const isNameFilled = name.trim().length > 0;
  const isDescriptionFilled = description.trim().length > 0;
  const isFormValid = isNameFilled && isDescriptionFilled;
  console.log("accesstoken", getAccessToken());
  const handleContinue = async () => {
    if (!isFormValid) return;

    try {
      const res = await createStore({ name, description });

      if (res.success) {
        // ✅ SUCCESS
        alert(res.message); // atau toast
        console.log("Store created:", res.data);

        // contoh redirect
        // router.push(`/store/${res.data?.slug}`);
      } else {
        // ❌ FAILED tapi 200 OK
        alert(res.message);
      }
    } catch (error: any) {
      const apiError = error.response?.data;

      if (apiError?.errors) {
        console.log(apiError.errors.name?.[0]); // error per field
      }

      alert(apiError?.message ?? "Gagal membuat store");
    }
  };
  const isUserStore = async () => {
    try {
      const res = await getStoreBySeller();
      const data = res.data;
      setIsStore(true);
    } catch (error) {
      const apiError = error.response?.data;

      if (apiError?.code === 404 | apiError?.code === 401) {
        console.log(apiError.code); // error per field
        setIsStore(false);
      }
    }
  };

useEffect(() => {
  isUserStore();
}, []);

  return (
    <>
      {!isStore ? (
        <div>
          <StoreCreatedForm
            name={name}
            setName={setName}
            description={description}
            setDescription={setDescription}
          />
          <Button onClick={handleContinue}>Buat Store</Button>
        </div>
      ) : (
        <div>
          <p>kamu sudah punya store</p>
        </div>
      )}
    </>
  );
}
