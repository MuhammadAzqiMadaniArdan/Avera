"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { ChevronRight } from "lucide-react";

const MAP: Record<string, string> = {
  home: "Beranda",
  products: "Produk Saya",
  create: "Tambah Produk",
};

function titleCase(str: string) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

export function Breadcrumbs() {
  const pathname = usePathname();
  const segments = pathname.split("/").filter(Boolean);

  const allSegments = ["Home", ...segments];

  return (
    <nav className="flex items-center text-sm text-muted-foreground">
      {allSegments.map((seg, idx) => {
        const href = "/" + allSegments.slice(1, idx + 1).join("/");
        const label = MAP[seg] ?? titleCase(seg);

        return (
          <div key={idx} className="flex items-center">
            {idx > 0 && (
              <ChevronRight className="self-end mx-1 h-4 w-4 text-muted-foreground" />
            )}{" "}
            <Link
              href={href || "/"}
              className={
                idx === allSegments.length - 1
                  ? "text-foreground font-medium"
                  : ""
              }
            >
              {label}
            </Link>
          </div>
        );
      })}
    </nav>
  );
}
