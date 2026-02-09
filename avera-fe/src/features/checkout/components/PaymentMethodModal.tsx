// components/PaymentMethodModal.tsx
"use client";

import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";

interface Props {
  open: boolean;
  value: "cod" | "midtrans";
  onChange: (v: "cod" | "midtrans") => void;
  onClose: () => void;
  onSubmit: () => void;
}

export default function PaymentMethodModal({
  open,
  value,
  onChange,
  onClose,
  onSubmit,
}: Props) {
  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent className="max-w-md">
        <DialogHeader>
          <DialogTitle>Select Payment Method</DialogTitle>
        </DialogHeader>

        <RadioGroup
          value={value}
          onValueChange={(v) => onChange(v as "cod" | "midtrans")}
          className="space-y-3"
        >
          <div className="flex items-center gap-4 space-x-4 border p-3 rounded">
            <RadioGroupItem value="cod" id="cod" />
            <Label htmlFor="cod" className="flex-1 cursor-pointer">
              Cash on Delivery
            </Label>
          </div>

          <div className="flex items-center gap-4 space-x-2 border p-3 rounded">
            <RadioGroupItem value="midtrans" id="midtrans" />
            <Label htmlFor="midtrans" className="flex-1 cursor-pointer">
              Online Payment (Midtrans)
            </Label>
          </div>
        </RadioGroup>

        <div className="flex justify-end gap-2 mt-4">
          <Button variant="outline" onClick={onClose}>
            Cancel
          </Button>
          <Button onClick={() => {
              onSubmit();
              onClose();
            }}>Confirm</Button>
        </div>
      </DialogContent>
    </Dialog>
  );
}
