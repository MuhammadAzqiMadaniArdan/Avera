"use client";
import { useState } from "react";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import CategorySelect from "./CategorySelect";
import { Category } from "@/features/category/types";
interface CreateProductFormProps {
  name: string;
  setName: (v: string) => void;
  categoryId: string;
  categoryList: Category[];
  setCategoryId: (v: string) => void;
  description: string;
  setDescription: (v: string) => void;
  categoryLoading: boolean;
}

export function CreateProductForm({
  name,
  setName,
  categoryId,
  categoryList,
  setCategoryId,
  description,
  setDescription,
  categoryLoading,
}: CreateProductFormProps) {
  return (
    <div className="space-y-4">
      {/* Nama */}
      <div className="flex flex-col gap-1">
        <label className="text-sm font-medium">Nama Produk</label>
        <Input
          maxLength={255}
          value={name}
          onChange={(e) => setName(e.target.value)}
          placeholder="Nama Merek + Tipe Produk + Fitur"
        />
        <div className="text-xs text-muted-foreground">
          {name.length}/255 karakter
        </div>
      </div>

      {/* Category */}
      <div className="flex flex-col gap-1">
        <label className="text-sm font-medium">Kategori</label>
        <CategorySelect
          categories={categoryList}
          value={categoryId}
          onChange={setCategoryId}
          disabled={categoryLoading}
        />
      </div>

      {/* Description */}
      <div className="flex flex-col gap-1">
        <label className="text-sm font-medium">Deskripsi Produk</label>
        <Textarea
          maxLength={100}
          value={description}
          onChange={(e) => setDescription(e.target.value)}
          placeholder="Deskripsi produk"
        />
        <div className="text-xs text-muted-foreground">
          {description.length}/100 karakter
        </div>
      </div>
    </div>
  );
}
