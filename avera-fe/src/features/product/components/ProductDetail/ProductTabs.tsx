import { PaginatedReviews } from "@/features/reviews/types";
import ReviewsTab from "./ReviewsTab";

export default function ProductTabs({
  activeTab,
  setActiveTab,
  description,
  reviews,
  getReviewData,
}: {
  activeTab: "spec" | "description" | "reviews";
  setActiveTab: React.Dispatch<
    React.SetStateAction<"spec" | "description" | "reviews">
  >;
  description: string;
  reviews: PaginatedReviews;
  getReviewData: (page: number, filterRating: number) => void;
}) {
  return (
    <section className="max-w-7xl mx-auto px-4 py-8">
      <div className="bg-white border rounded-xl">
        <div className="flex border-b">
          {["spec", "description", "reviews"].map((t) => (
            <button
              key={t}
              onClick={() => setActiveTab(t as any)}
              className={`px-6 py-4 text-sm ${
                activeTab === t ? "border-b-2 border-black" : "text-gray-500"
              }`}
            >
              {t.toUpperCase()}
            </button>
          ))}
        </div>

        <div className="p-6 text-sm">
          {activeTab === "spec" && <p>Product Specification…</p>}
          {activeTab === "description" && <p>{description}</p>}
          {activeTab === "reviews" && (
            <ReviewsTab reviews={reviews} getReviewData={getReviewData} />
          )}
        </div>
      </div>
    </section>
  );
}
