"use client";

import { formatSold } from "@/lib/utils/formatSold";
import Image from "next/image";
import Link from "next/link";

interface ProductCardProps {
  id: string;
  storeId: string;
  name: string;
  price: number;
  image: string;
  sold: number;
  slug: string;
  status: "public" | "limited";
}

export default function ProductCard({
  id,
  storeId,
  name,
  price,
  image,
  sold,
  slug,
  status,
}: ProductCardProps) {
  const isLimited = status === "limited";
  const productUrl = `/product/${slug}-i.${storeId}.${id}`;

  return (
    <Link href={productUrl} className="group block h-full">
      <div
        className="
                  w-40 sm:w-44 md:w-44
          flex h-full flex-col
          bg-white
          rounded-xl
          border border-gray-100
          overflow-hidden
          transition-all duration-200
          hover:shadow-lg
          hover:-translate-y-0.5
        "
      >
        {/* IMAGE */}
        <div className="relative aspect-square bg-gray-50">
          <Image
            src={image}
            alt={name}
            fill
            className={`object-cover transition-transform duration-300 group-hover:scale-105 ${
              isLimited ? "blur-sm" : ""
            }`}
          />

          {isLimited && (
            <div className="absolute inset-0 flex items-center justify-center bg-black/50 backdrop-blur-[2px]">
              <span className="text-white text-xl font-bold">18+</span>
            </div>
          )}
        </div>

        {/* CONTENT */}
        <div className="flex flex-col p-2 flex-1 justify-between">
          {/* TITLE — FIX 2 BARIS */}
          <h3
            title={name}
            className="
              text-sm
              font-medium
              leading-snug
              text-gray-900
              line-clamp-2
              min-h-[2.75rem]
            "
          >
            {name}
          </h3>

          {/* PUSH FOOTER TO BOTTOM */}
          <div className="mt-auto pt-2 flex items-center justify-between">
            <p className="text-green-600 font-semibold text-sm">
              Rp {price.toLocaleString("id-ID")}
            </p>

            {sold !== undefined && (
              <span className="text-xs text-gray-500">
                {formatSold(sold)} terjual
              </span>
            )}
          </div>
        </div>
      </div>
    </Link>
  );
}
