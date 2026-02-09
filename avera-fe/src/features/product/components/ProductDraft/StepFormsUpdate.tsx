"use client";
import React from "react";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { PriceInput } from "../ProductCreate/PriceInput";
import { PhotoUpload } from "../ProductCreate/PhotoUpload";
import { Select } from "@radix-ui/react-select";

interface StepFormsUpdateProps {
  activeTab: string;
  name: string;
  setName: React.Dispatch<React.SetStateAction<string>>;
  description: string;
  setDescription: React.Dispatch<React.SetStateAction<string>>;
  category: string;
  setCategory: React.Dispatch<React.SetStateAction<string>>;
  images: string[];
  setImages: React.Dispatch<React.SetStateAction<string[]>>;
  price: number;
  setPrice: React.Dispatch<React.SetStateAction<number>>;
  stock: number;
  setStock: React.Dispatch<React.SetStateAction<number>>;
  minBuy: number;
  setMinBuy: React.Dispatch<React.SetStateAction<number>>;
  wholesalePrice: number;
  setWholesalePrice: React.Dispatch<React.SetStateAction<number>>;
  weight: number;
  setWeight: React.Dispatch<React.SetStateAction<number>>;
  length: number;
  setLength: React.Dispatch<React.SetStateAction<number>>;
  width: number;
  setWidth: React.Dispatch<React.SetStateAction<number>>;
  height: number;
  setHeight: React.Dispatch<React.SetStateAction<number>>;
  sku: string;
  setSku: React.Dispatch<React.SetStateAction<string>>;
}

export function StepFormsUpdate({
  activeTab,
  name,
  setName,
  description,
  setDescription,
  category,
  setCategory,
  images,
  setImages,
  price,
  setPrice,
  stock,
  setStock,
  minBuy,
  setMinBuy,
  wholesalePrice,
  setWholesalePrice,
  weight,
  setWeight,
  length,
  setLength,
  width,
  setWidth,
  height,
  setHeight,
  sku,
  setSku,
}: StepFormsUpdateProps) {
  switch (activeTab) {
    case "info":
      return (
        <div className="space-y-4">
          <PhotoUpload images={images} setImages={setImages} />

          <div className="flex flex-col gap-1">
            <label className="text-sm font-medium">Nama Produk</label>
            <Input
              placeholder="Nama Merek + Tipe Produk + Fitur"
              value={name}
              onChange={(e) => setName(e.target.value)}
            />
            <div className="text-xs text-muted-foreground">
              {name.length}/255 karakter
            </div>
          </div>

          <div className="flex flex-col gap-1">
            <label className="text-sm font-medium">Kategori</label>
            <Select
              value={category}
              onValueChange={setCategory}
              placeholder="Pilih kategori"
            >
              <option value="cat1">Kategori 1</option>
              <option value="cat2">Kategori 2</option>
            </Select>
          </div>

          <div className="flex flex-col gap-1">
            <label className="text-sm font-medium">Deskripsi Produk</label>
            <Textarea
              placeholder="Deskripsi produk"
              value={description}
              onChange={(e) => setDescription(e.target.value)}
            />
            <div className="text-xs text-muted-foreground">
              {description.length}/100 karakter
            </div>
          </div>
        </div>
      );

    case "sales":
      return (
        <div className="space-y-4">
          <h3 className="font-medium">Informasi Penjualan</h3>

          <div className="flex gap-2 items-center">
            <label className="w-32 text-sm">Harga</label>
            <PriceInput value={price} onChange={setPrice} />
          </div>

          <div className="flex gap-2 items-center">
            <label className="w-32 text-sm">Stok</label>
            <Input
              type="number"
              value={stock}
              onChange={(e) => setStock(Number(e.target.value))}
              min={0}
            />
          </div>

          <div className="flex gap-2 items-center">
            <label className="w-32 text-sm">Min Pembelian</label>
            <Input
              type="number"
              value={minBuy}
              onChange={(e) => setMinBuy(Number(e.target.value))}
              min={1}
            />
          </div>

          <div className="flex gap-2 items-center">
            <label className="w-32 text-sm">Harga Grosir</label>
            <PriceInput value={wholesalePrice} onChange={setWholesalePrice} />
          </div>
        </div>
      );

    case "shipping":
      return (
        <div className="space-y-4">
          <h3 className="font-medium">Pengiriman</h3>

          <div className="flex gap-2 items-center">
            <label className="w-32 text-sm">Berat (gram)</label>
            <Input
              type="number"
              value={weight}
              onChange={(e) => setWeight(Number(e.target.value))}
            />
          </div>

          <div className="flex gap-2 items-center">
            <label className="w-32 text-sm">Dimensi (P x L x T)</label>
            <Input
              type="number"
              placeholder="P"
              value={length}
              onChange={(e) => setLength(Number(e.target.value))}
            />
            <Input
              type="number"
              placeholder="L"
              value={width}
              onChange={(e) => setWidth(Number(e.target.value))}
            />
            <Input
              type="number"
              placeholder="T"
              value={height}
              onChange={(e) => setHeight(Number(e.target.value))}
            />
          </div>
        </div>
      );

    case "others":
      return (
        <div className="space-y-4">
          <h3 className="font-medium">Lainnya</h3>
          <div className="flex gap-2 items-center">
            <label className="w-32 text-sm">SKU</label>
            <Input value={sku} onChange={(e) => setSku(e.target.value)} />
          </div>
        </div>
      );

    default:
      return null;
  }
}
