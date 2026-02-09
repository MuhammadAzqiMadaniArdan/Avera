"use client";
import React from "react";

interface RecommendedCardCreateProps {
  nameFilled: boolean;
  categoryFilled: boolean;
  descriptionFilled: boolean;
}

const RecommendedCardCreate: React.FC<RecommendedCardCreateProps> = ({
  nameFilled,
  categoryFilled,
  descriptionFilled,
}) => {
  const items = [
    { text: "Nama produk diisi", done: nameFilled },
    { text: "Kategori produk dipilih", done: categoryFilled },
    { text: "Deskripsi produk diisi", done: descriptionFilled },
  ];

  return (
    <div className="space-y-2">
      <h4 className="font-medium text-base bg-accent p-2 rounded-lg text-white">
        Rekomendasi
      </h4>
      <ul className="space-y-1 text-sm text-muted-foreground">
        {items.map((item, index) => (
          <li key={index}>
            {item.done ? "✅" : "⚪"} {item.text}
          </li>
        ))}
      </ul>
    </div>
  );
};

export default RecommendedCardCreate;
