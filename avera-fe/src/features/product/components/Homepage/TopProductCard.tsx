"use client";

import Image from "next/image";
import Link from "next/link";

interface TopProductCardProps {
  id: string;
  storeId: string;
  name: string;
  image: string;
  sold: number;
  slug: string;
}

export default function TopProductCard({
  id,
  storeId,
  name,
  image,
  sold,
  slug,
}: TopProductCardProps) {
  const productUrl = `/product/${slug}-i.${storeId}.${id}`;

  return (
    <Link href={productUrl} className="group block">
      <div
        className="
          relative
          w-40 sm:w-44 md:w-48
          bg-white
          rounded-lg
          overflow-hidden
          shadow-sm
          hover:shadow-lg
          transition-all
          duration-200
        "
      >
        {/* IMAGE */}
        <div className="relative aspect-square">
          <Image
            src={image}
            alt={name}
            fill
            className="object-cover transition-transform duration-300 group-hover:scale-105"
          />

          {/* TOP BADGE */}
          <div className="absolute top-2 left-2 px-2 py-1 text-white text-xs sm:text-sm font-bold rounded-t-md rounded-b-full  bg-primary ">
            Top
          </div>

          {/* STICKY SOLD */}
          <div className="absolute bottom-0 left-0 w-full bg-black/0 px-2 py-1">
            <span className="text-white text-xs sm:text-sm font-medium">
              {sold} terjual
            </span>
          </div>
        </div>

        {/* NAME */}
        <div className="p-2">
          <h3
            title={name}
            className="text-sm sm:text-base md:text-lg font-semibold text-gray-900 truncate"
          >
            {name}
          </h3>
        </div>
      </div>
    </Link>
  );
}
