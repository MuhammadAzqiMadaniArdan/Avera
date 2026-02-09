"use client";
import { useState, useEffect } from "react";
import { Input } from "@/components/ui/input";
import { formatCurrency } from "@/lib/utils/formatCurrency";

export function PriceInput({
  value,
  onChange,
}: {
  value: number;
  onChange: (v: number) => void;
}) {
  const [display, setDisplay] = useState("");

  useEffect(() => {
    setDisplay(formatCurrency(value));
  }, [value]);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const numeric = parseInt(e.target.value.replace(/\D/g, ""), 10) || 0;
    onChange(numeric);
  };

  return (
    <div className="flex items-center gap-2">
      <Input value={display} onChange={handleChange} placeholder="0" />
      <span>Rp</span>
    </div>
  );
}
