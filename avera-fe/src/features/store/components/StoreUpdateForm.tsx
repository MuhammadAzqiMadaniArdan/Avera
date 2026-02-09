"use client";

import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";

interface StoreUpdateFormProps {
  onSubmit?: (data: { name: string; description: string }) => void;
}

export function StoreUpdateForm({ onSubmit }: StoreUpdateFormProps) {
  const [name, setName] = useState("");
  const [description, setDescription] = useState("");

  const NAME_MAX = 50; // maksimal 50 karakter
  const DESC_MAX = 200; // maksimal 200 karakter

  const isFormValid = name.trim() && description.trim();

  const handleSubmit = () => {
    if (!isFormValid) return;
    onSubmit?.({ name, description });
  };

  return (
    <div className="space-y-4 mt-4">
      <div>
        <label className="block text-sm font-medium mb-1">
          Nama Store
        </label>
        <Input
          value={name}
          onChange={(e) => setName(e.target.value)}
          placeholder="Masukkan nama store"
          maxLength={NAME_MAX}
        />
        <p className="text-xs text-gray-400 mt-1">
          {name.length}/{NAME_MAX} karakter
        </p>
      </div>

      <div>
        <label className="block text-sm font-medium mb-1">
          Deskripsi
        </label>
        <Textarea
          value={description}
          onChange={(e) => setDescription(e.target.value)}
          placeholder="Deskripsi singkat store"
          maxLength={DESC_MAX}
        />
        <p className="text-xs text-gray-400 mt-1">
          {description.length}/{DESC_MAX} karakter
        </p>
      </div>

      <Button disabled={!isFormValid} onClick={handleSubmit}>
        Buat Store
      </Button>
    </div>
  );
}
