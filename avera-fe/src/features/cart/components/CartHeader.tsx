"use client";

import Image from "next/image";
import Link from "next/link";
import { ArrowLeft } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

interface Props {
  search: string;
  onSearchChange: (value: string) => void;
}

export function CartHeader({ search, onSearchChange }: Props) {
  return (
    <div className="sticky top-0 z-50 flex flex-wrap items-center justify-between gap-4 border-b bg-white p-4 px-20 shadow">
      <div className="flex flex-1 items-center gap-2 min-w-0">
        <Link href="/">
          <Button variant="ghost" className="p-1">
            <ArrowLeft size={20} />
          </Button>
        </Link>

        <span className="text-gray-400">|</span>

        <Link href="/" className="flex items-center gap-2 min-w-0">
          <Image src="/avera-nav.svg" alt="Logo" width={100} height={30} />
        </Link>

        <span className="text-gray-400">|</span>
        <h2 className="truncate text-lg font-medium">Shopping Cart</h2>
      </div>

      <div className="flex-1 max-w-md">
        <Input
          placeholder="Search in your cart..."
          value={search}
          onChange={(e) => onSearchChange(e.target.value)}
        />
      </div>
    </div>
  );
}
