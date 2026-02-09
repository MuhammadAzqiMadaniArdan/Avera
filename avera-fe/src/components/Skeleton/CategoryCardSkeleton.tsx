"use client";

export default function CategorySkeleton() {
  return (
    <div className="flex flex-col items-center flex-shrink-0 w-24 animate-in gap-1">
      <div className="w-20 h-20 bg-gray-300 rounded-lg" />
      <div className="h-4 bg-gray-300 rounded w-16" />
    </div>
  );
}
