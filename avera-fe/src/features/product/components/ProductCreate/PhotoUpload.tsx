"use client";
import React from "react";

interface PhotoUploadProps {
  photos: string[];
  setPhotos: React.Dispatch<React.SetStateAction<string[]>>;
}

export const PhotoUpload: React.FC<PhotoUploadProps> = ({ photos, setPhotos }) => {
  const handleAdd = () => {
    const newPhoto = `photo_${photos.length + 1}.jpg`;
    setPhotos([...photos, newPhoto]);
  };

  const handleRemove = (index: number) => {
    setPhotos(photos.filter((_, i) => i !== index));
  };

  return (
    <div className="flex flex-col gap-2">
      <div className="flex gap-2 flex-wrap">
        {photos.map((photo, idx) => (
          <div key={idx} className="relative w-20 h-20 bg-gray-200 flex items-center justify-center">
            <span>{photo}</span>
            <button
              className="absolute top-0 right-0 text-red-500"
              onClick={() => handleRemove(idx)}
            >
              x
            </button>
          </div>
        ))}
      </div>
      <button
        className="px-2 py-1 bg-blue-500 text-white rounded"
        onClick={handleAdd}
      >
        Tambah Foto
      </button>
    </div>
  );
};
