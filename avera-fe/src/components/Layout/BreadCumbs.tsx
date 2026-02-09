"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { ChevronRight } from "lucide-react";

const MAP: Record<string, string> = {
  dashboard: "Beranda",
  products: "Produk Saya",
  create: "Tambah Produk",
};

export function Breadcrumbs() {
  const pathname = usePathname();
  const segments = pathname.split("/").filter(Boolean);

  return (
    <nav className="flex items-center text-sm text-muted-foreground">
      {segments.map((seg, idx) => {
        const href = "/" + segments.slice(0, idx + 1).join("/");
        const label = MAP[seg] ?? seg;

        return (
          <div key={idx} className="flex items-center">
            {idx > 0 && <ChevronRight className="mx-2 h-4 w-4" />}
            <Link
              href={href}
              className={idx === segments.length - 1 ? "text-foreground font-medium" : ""}
            >
              {label}
            </Link>
          </div>
        );
      })}
    </nav>
  );
}
