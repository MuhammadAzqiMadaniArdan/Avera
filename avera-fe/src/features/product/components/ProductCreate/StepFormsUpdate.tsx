"use client";
import { useState } from "react";
import { PhotoUpload } from "./PhotoUpload";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Select } from "@/components/ui/select";
import { PriceInput } from "./PriceInput";

export function StepFormsUpdate({ activeTab }: { activeTab: string }) {
  const [name, setName] = useState("");
  const [category, setCategory] = useState("");
  const [description, setDescription] = useState("");
  const [price, setPrice] = useState(0);
  const [stock, setStock] = useState(0);
  const [minBuy, setMinBuy] = useState(1);
  const [wholesalePrice, setWholesalePrice] = useState(0);
  const [weight, setWeight] = useState(0);
  const [length, setLength] = useState(0);
  const [width, setWidth] = useState(0);
  const [height, setHeight] = useState(0);
  const [sku, setSku] = useState("");

  switch (activeTab) {
    case "info":
      return (
        <div className="space-y-4">
          <PhotoUpload />

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
