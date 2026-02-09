"use client";

import Select from "react-select";
import { Category, flattenCategories } from "@/features/category/types";

interface Props {
  categories: Category[];
  value: string;
  onChange: (id: string) => void;
  disabled?: boolean;
}

export default function CategorySelect({
  categories,
  value,
  onChange,
  disabled,
}: Props) {
  const options = flattenCategories(categories);

  const selected = options.find((opt) => opt.value === value) ?? null;

  return (
    <Select
      options={options}
      value={selected}
onChange={(opt) => onChange(opt?.value ?? "")}
      placeholder="Pilih kategori"
      isClearable
      isDisabled={disabled}
      classNamePrefix="react-select"
    />
  );
}
