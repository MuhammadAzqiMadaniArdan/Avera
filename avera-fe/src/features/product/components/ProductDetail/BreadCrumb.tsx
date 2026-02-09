import { BreadcrumbProps } from "@/features/category/types";
import { ChevronRight } from "lucide-react";
import Link from "next/link";
import React from "react";

export default function Breadcrumb({ path, productName }: BreadcrumbProps) {
  return (
    <div className="max-w-7xl mx-auto px-4 pt-6">
      <div className="flex items-center text-sm text-gray-500 flex-wrap">
        {path.map((c, i) => (
          <React.Fragment key={i}>
            <Link href={`/category/${c.slug}`} className="flex items-center">
              <span className="hover:text-black cursor-pointer">{c.name}</span>
              {i < path.length && <ChevronRight className="h-4 w-4 mx-1" />}
            </Link>
          </React.Fragment>
        ))}
        <span className="text-black font-medium line-clamp-1 ml-1">
          {productName}
        </span>
      </div>
    </div>
  );
}