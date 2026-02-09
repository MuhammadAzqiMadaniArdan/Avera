"use client";

export default function ProductCardSkeleton() {
  return (
    <div className="rounded-lg border bg-white p-3 animate-in">
      <div className="w-full aspect-square rounded-md bg-gray-200 dark:bg-gray-800" />

      <div className="mt-3 h-4 w-3/4 rounded bg-gray-200 dark:bg-gray-800" />

      <div className="mt-2 h-3 w-1/3 rounded bg-gray-200 dark:bg-gray-800" />

      <div className="mt-3 h-4 w-1/2 rounded bg-gray-200 dark:bg-gray-800" />
    </div>
  );
}
