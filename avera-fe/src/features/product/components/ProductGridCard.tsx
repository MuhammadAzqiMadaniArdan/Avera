"use client";

import { useState } from "react";
import Link from "next/link";
import { MoreVertical, Eye, Archive, Pencil } from "lucide-react";

import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

import {
  Dialog,
  DialogContent,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogDescription,
} from "@/components/ui/dialog";

import { Button } from "@/components/ui/button";

export function ProductGridCard({ product }: { product: any }) {
  const [openArchive, setOpenArchive] = useState(false);

  return (
    <>
      <Link href={`/seller/products/${product.slug}`} className="relative rounded-lg border bg-white p-3 transition hover:shadow-sm">
        {/* IMAGE */}
        <div className="aspect-square rounded bg-muted mb-3" />

        {/* STATUS */}
        <span className="inline-block rounded bg-gray-200 px-2 py-0.5 text-xs mb-1">
          {product.status}
        </span>

        {/* NAME */}
        <p className="font-medium line-clamp-2">{product.name}</p>

        {/* SKU */}
        <p className="text-xs text-muted-foreground">
          SKU Induk: {product.sku}
        </p>

        {/* PRICE */}
        <p className="mt-1 font-semibold">
          Rp {product.price.toLocaleString("id-ID")}
        </p>

        {/* META */}
        <div className="mt-2 flex justify-between text-xs text-muted-foreground">
          <span>Terjual {product.sold}</span>
          <span>Stok {product.stock}</span>
        </div>

        {/* VIEWS */}
        <div className="mt-2 flex items-center gap-1 text-xs text-muted-foreground">
          <Eye className="h-3.5 w-3.5" />
          <span>{product.views ?? 0} dilihat</span>
        </div>

        {/* ACTION */}
        <div className="absolute top-2 right-2">
          <DropdownMenu modal={false}>
            <DropdownMenuTrigger asChild>
              <button className="rounded p-1 hover:bg-muted">
                <MoreVertical className="h-4 w-4" />
              </button>
            </DropdownMenuTrigger>

            <DropdownMenuContent align="end" className="w-40">
              <DropdownMenuItem asChild>
                <Link
                  href={`/seller/products/${product.id}/edit`}
                  className="flex items-center gap-2"
                >
                  <Pencil className="h-4 w-4" />
                  Edit Produk
                </Link>
              </DropdownMenuItem>

              <DropdownMenuItem
                className="flex items-center gap-2 text-red-600"
                onClick={() => setOpenArchive(true)}
              >
                <Archive className="h-4 w-4" />
                Arsipkan
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </Link>

      {/* ARCHIVE MODAL */}
      <Dialog open={openArchive} onOpenChange={setOpenArchive}>
        <DialogContent className="sm:max-w-md">
          <DialogHeader>
            <DialogTitle>Arsipkan Produk</DialogTitle>
            <DialogDescription>
              Apakah kamu yakin ingin mengarsipkan produk
              <span className="font-medium"> {product.name}</span>? Produk tidak
              akan tampil di toko.
            </DialogDescription>
          </DialogHeader>

          <DialogFooter className="gap-2 sm:gap-0">
            <Button variant="outline" onClick={() => setOpenArchive(false)}>
              Batal
            </Button>
            <Button
              variant="destructive"
              onClick={() => {
                // TODO: call archive action
                console.log("Archive product", product.id);
                setOpenArchive(false);
              }}
            >
              Arsipkan
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
}
