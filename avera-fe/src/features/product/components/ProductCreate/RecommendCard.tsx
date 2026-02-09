"use client";
import React, { useState } from "react";
import { CheckCircle, Circle } from "lucide-react";
import { Tooltip, TooltipContent, TooltipTrigger } from "@/components/ui/tooltip";

interface RecommendCardProps {
  isPhotoUploaded: boolean;
  isNameValid: boolean;
  isDescriptionValid: boolean;
}

const RecommendCard: React.FC<RecommendCardProps> = ({
  isPhotoUploaded,
  isNameValid,
  isDescriptionValid,
}) => {
  const [activeIndex, setActiveIndex] = useState<number | null>(null);

  const items = [
    {
      text: "Tambahkan minimal 3 foto produk",
      done: isPhotoUploaded,
      tip: "Produk harus memiliki minimal 3 foto agar lebih menarik",
    },
    {
      text: "Nama produk 25–100 karakter",
      done: isNameValid,
      tip: "Nama produk harus antara 25–100 karakter",
    },
    {
      text: "Deskripsi min 100 karakter",
      done: isDescriptionValid,
      tip: "Deskripsi produk harus minimal 100 karakter",
    },
  ];

  return (
    <div className="space-y-2">
      <h4 className="font-medium text-base bg-accent p-2 rounded-lg text-white">
        Rekomendasi
      </h4>
      <ul className="space-y-1 text-sm text-muted-foreground">
        {items.map((item, index) => (
          <li key={index} className="flex items-center gap-2 relative">
            {item.done ? (
              <CheckCircle className="w-4 h-4 text-green-500" />
            ) : (
              <>
                {/* Tooltip desktop */}
                <Tooltip>
                  <TooltipTrigger>
                    <Circle className="w-4 h-4 text-gray-300 cursor-pointer hidden sm:inline-block" />
                  </TooltipTrigger>
                  <TooltipContent className="bg-gray-800 text-white text-xs p-2 rounded shadow-lg">
                    {item.tip}
                  </TooltipContent>
                </Tooltip>

                {/* Mobile tap */}
                <Circle
                  className="w-4 h-4 text-gray-300 cursor-pointer sm:hidden"
                  onClick={() =>
                    setActiveIndex(activeIndex === index ? null : index)
                  }
                />
                {activeIndex === index && (
                  <div className="absolute left-6 top-0 z-50 bg-gray-800 text-white text-xs p-2 rounded shadow-lg w-48">
                    {item.tip}
                  </div>
                )}
              </>
            )}
            <span>{item.text}</span>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default RecommendCard;
