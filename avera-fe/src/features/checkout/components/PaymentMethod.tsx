// components/PaymentMethod.tsx
"use client";

import { Button } from "@/components/ui/button";

interface Props {
  method: "cod" | "midtrans";
  onChange: () => void;
}

export default function PaymentMethod({ method, onChange }: Props) {
  return (
    <div className="bg-white rounded-md p-4 flex justify-between items-center">
      <div>
        <p className="font-medium">Payment Method</p>
        <p className="text-sm text-gray-600">
          {method === "cod" ? "Cash on Delivery" : "Online Payment (Midtrans)"}
        </p>
      </div>

      <Button variant="outline" onClick={onChange}>
        Change
      </Button>
    </div>
  );
}
