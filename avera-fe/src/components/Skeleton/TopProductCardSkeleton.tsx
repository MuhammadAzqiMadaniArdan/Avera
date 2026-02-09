export default function TopProductCardSkeleton() {
  return (
    <div className=" w-40 sm:w-44 md:w-48 animate-in">
      <div className="w-full h-40 bg-gray-200 rounded-lg" />
      <div className="mt-2 h-4 bg-gray-200 rounded w-3/4" />
      <div className="mt-1 h-4 bg-gray-200 rounded w-1/2" />
    </div>
  );
}
