"use client";

import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { useState, useEffect } from "react";
import { CheckoutShipment } from "../types";
import { formatCurrency } from "@/lib/utils/formatCurrency";

export default function ShippingModal({
  open,
  shipments,
  onClose,
  onConfirm,
}: {
  open: boolean;
  shipments: CheckoutShipment[];
  onClose: () => void;
  onConfirm: (shipmentId: string,activeShipmentId : string) => void;
}) {
  const [selected, setSelected] = useState<string | null>(null);
const [initialSelected, setInitialSelected] = useState<string | null>(null);

  useEffect(() => {
    const active = shipments.find((s) => s.is_selected);
    const activeId = active?.id ?? null;
    setSelected(activeId);
    setInitialSelected(activeId);
  }, [shipments]);

  const isUnchanged = selected === initialSelected;

  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent  className="max-h-[85vh] w-full sm:max-w-lg flex flex-col"
        style={{ maxHeight: "85vh" }}>
        <DialogHeader>
          <DialogTitle>Select Shipping Option</DialogTitle>
        </DialogHeader>

        <div className="flex-1 space-y-2 overflow-y-auto no-scrollbar">
          {shipments.map((shipment) => (
           <label
              key={shipment.id}
              className={`flex items-start gap-3 border p-3 rounded cursor-pointer mb-2
                ${
                  selected === shipment.id
                    ? "border-primary bg-green-50"
                    : "hover:bg-gray-50"
                }
              `}
            >
              <input
                type="radio"
                name="shipment"
                checked={selected === shipment.id}
                onChange={() => setSelected(shipment.id)}
              />

              <div className="flex-1">
                <p className="font-medium">
                  {shipment.courier_name} - {shipment.service}
                </p>
                <p className="text-xs text-gray-500">
                  Estimasi {shipment.min_days} - {shipment.max_days} hari
                </p>
                <p className="font-semibold mt-1">
                  {formatCurrency(shipment.cost)}
                </p>
                {shipment.id === initialSelected && (
                  <span className="inline-block mt-1 text-xs text-primary">
                    Currently selected
                  </span>
                )}
              </div>
            </label>
          ))}
        </div>

        <div className="flex justify-end gap-2 mt-4">
          <Button variant="outline" onClick={onClose}>
            Cancel
          </Button>
          <Button
            disabled={!selected || isUnchanged}
            onClick={() => {
              if (!selected || isUnchanged) {
                onClose(); 
                return;
              }

              onConfirm(selected,initialSelected);
              onClose();
            }}
          >
            Confirm
          </Button>
        </div>
      </DialogContent>
    </Dialog>
  );
}
