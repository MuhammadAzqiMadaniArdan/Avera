"use client";

import Image from "next/image";
import { Trash2 } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { CartItemData } from "../types";
import Link from "next/link";

export function CartItemRow({
  item,
  checked,
  onToggle,
  onQtyChange,
  onDelete,
}: {
  item: CartItemData;
  checked: boolean;
  onToggle: () => void;
  onQtyChange: (qty: number) => void;
  onDelete: () => void;
}) {
  const product = item.product;
  const price = product.price;
  const qty = item.quantity;
  const productUrl = `/product/${product.slug}-i.${product.store_id}.${product.id}`;

  return (
    <div className="flex flex-col gap-4 rounded-lg bg-white p-4 shadow-sm md:grid md:grid-cols-[0.5fr_3fr_1fr_1fr_1fr_2fr] md:items-center">
      {/* Checkbox */}
      <div className="flex justify-center">
        <input
          type="checkbox"
          checked={checked}
          onChange={onToggle}
          className="cursor-pointer"
        />
      </div>

      {/* Product info */}
      <div className="flex items-center gap-4">
        <Link href={productUrl} className="relative h-24 w-24 flex-shrink-0 overflow-hidden rounded">
          <Image
            src={product.primaryImage ? product.primaryImage :  "https://loremflickr.com/24/24"}
            alt={product.name}
            fill
            sizes="96px"
            className="object-cover"
          />
        </Link>

        <Link href={productUrl}  className="min-w-0">
          <h3 className="truncate font-medium">
            {product.name}
          </h3>

          {/* {product?.variation_name && (
            <p className="text-sm text-gray-500">
              {product.variation_name}
            </p>
          )} */}

          {/* {item.saved_per_item && (
            <p className="mt-1 text-sm font-semibold text-green-600">
              Hemat Rp{" "}
              {(item.saved_per_item * qty).toLocaleString()}
            </p>
          )} */}
        </Link>
      </div>

      {/* Unit price */}
      <div className="font-semibold text-green-600 md:text-sm">
        Rp {price.toLocaleString()}
      </div>

      {/* Quantity */}
      <div className="flex items-center gap-1">
        <Button
          size="sm"
          variant="outline"
          onClick={() => onQtyChange(Math.max(1, qty - 1))}
        >
          -
        </Button>

        <Input
          type="number"
          className="w-16 text-center"
          value={qty}
          min={1}
          onChange={(e) =>
            onQtyChange(Math.max(1, Number(e.target.value) || 1))
          }
        />

        <Button
          size="sm"
          variant="outline"
          onClick={() => onQtyChange(qty + 1)}
        >
          +
        </Button>
      </div>

      {/* Total */}
      <div className="font-semibold">
        Rp {(price * qty).toLocaleString()}
      </div>

      {/* Delete */}
      <div className="flex justify-center">
        <Button variant="ghost" size="sm" onClick={onDelete}>
          <Trash2 size={16} />
        </Button>
      </div>
    </div>
  );
}
