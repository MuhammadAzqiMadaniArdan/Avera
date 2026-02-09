"use client";

import { useState } from "react";
import Image from "next/image";
import {
  Dialog,
  DialogContent,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Voucher } from "../types";

interface Props {
  store: string;
  vouchers: Voucher[];
  open: boolean;
  onClose: () => void;
  selectedVoucherId?: number;
  onSelect: (voucherId: number | null) => void;
  storeIcon?: string;
}

export function StoreVoucherModal({
  store,
  vouchers,
  open,
  onClose,
  selectedVoucherId,
  onSelect,
  storeIcon,
}: Props) {
  const [tempSelected, setTempSelected] = useState<number | null>(
    selectedVoucherId || null
  );

  const handleConfirm = () => {
    onSelect(tempSelected);
    onClose();
  };

  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent className="max-w-lg">
        <DialogHeader>
          <DialogTitle>Select Voucher for {store}</DialogTitle>
        </DialogHeader>

        <div className="mt-2 flex flex-col gap-3">
          {vouchers.map((v) => (
            <div
              key={v.id}
              className="flex cursor-pointer items-center justify-between rounded border p-2 hover:bg-gray-50"
              onClick={() => setTempSelected(v.id)}
            >
              {storeIcon && (
                <div className="relative h-10 w-10 flex-shrink-0">
                  <Image
                    src={storeIcon || "/placeholder.svg"}
                    alt={store}
                    fill
                    className="object-contain"
                  />
                </div>
              )}

              <div className="ml-3 flex-1">
                <p className="font-medium">
                  {v.code} - Rp {v.value.toLocaleString()}
                </p>
                <p className="text-sm text-gray-500">
                  Min Spend: Rp {v.minSpend.toLocaleString()}
                </p>
                <p className="text-xs text-gray-400">
                  Valid Until: {v.validUntil}
                </p>
              </div>

              <input
                type="radio"
                checked={tempSelected === v.id}
                onChange={() => setTempSelected(v.id)}
                className="h-4 w-4"
              />
            </div>
          ))}

          <div
            className="flex cursor-pointer items-center justify-between rounded border p-2 hover:bg-gray-50"
            onClick={() => setTempSelected(null)}
          >
            <p className="ml-3 text-gray-500">Do not use voucher</p>
            <input
              type="radio"
              checked={tempSelected === null}
              onChange={() => setTempSelected(null)}
              className="h-4 w-4"
            />
          </div>
        </div>

        <DialogFooter className="mt-4">
          <Button variant="outline" onClick={onClose}>
            Cancel
          </Button>
          <Button onClick={handleConfirm}>Confirm</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
