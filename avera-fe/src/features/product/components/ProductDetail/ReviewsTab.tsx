"use client"
import { Tooltip, TooltipContent, TooltipTrigger } from "@/components/ui/tooltip";
import { PaginatedReviews } from "@/features/reviews/types";
import { MoreHorizontal, Star, StarOff } from "lucide-react";
import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import ReviewReportModal from "./ReviewReportModal";



export default function ReviewsTab({
  reviews,
  getReviewData,
}: {
  reviews: PaginatedReviews;
  getReviewData: (page: number, filterRating: number) => void;
}) {
  const [filterRating, setFilterRating] = useState<0 | 1 | 2 | 3 | 4 | 5>(0);
  const [currentPage, setCurrentPage] = useState(reviews.meta.current_page);
  const [reportModalOpen, setReportModalOpen] = useState(false);
  const totalPages = reviews.meta.last_page;

  const handlePageChange = (page: number, filterRating: number) => {
    setCurrentPage(page);
    getReviewData(page, filterRating);
  };

  function RenderStars({ rating }: { rating: number }) {
    const stars = [];
    for (let i = 1; i <= 5; i++) {
      if (i <= rating) {
        stars.push(
          <Star
            key={i}
            className={`h-3.5 w-3.5 ${"fill-yellow-400 text-yellow-400"}`}
          />,
        );
      } else {
        stars.push(
          <Star key={i} className={`h-3.5 w-3.5 ${" text-gray-300"}`} />,
        );
      }
    }
    return <>{stars}</>;
  }
  return (
    <div>
      {/* Rating Filter */}
      <div className="flex gap-2 mb-4">
        {[0, 1, 2, 3, 4, 5].map((r) => (
          <label
            key={r}
            className={`cursor-pointer px-3 py-2 border rounded-lg transition ${
              filterRating === r
                ? "bg-black text-white border-black"
                : "bg-gray-100 text-gray-700 border-gray-300"
            }`}
          >
            <input
              type="radio"
              name="rating"
              value={r}
              className="hidden"
              checked={filterRating === r}
              onChange={() => {
                setFilterRating(r as 0 | 1 | 2 | 3 | 4 | 5);
                handlePageChange(1, r);
              }}
            />
            {r === 0 ? "All" : r}
          </label>
        ))}
      </div>

      {reviews.data.length !== 0 ? (
        <>
          {/* Review List */}
          <div className="flex flex-col gap-4">
            {reviews.data.map((r) => (
              <div
                key={r.id}
                className="border p-4 rounded-lg bg-gray-50 relative flex flex-col gap-2"
              >
                {/* User Info */}
                <div className="flex items-center justify-between">
                  <Link
                    href={`/user/${r.user.id}`}
                    className="flex items-center gap-3"
                  >
                    <Image
                      src={r.user.avatar ?? "http://loremflickr.com/80/80"}
                      alt={r.user.username}
                      width={40}
                      height={40}
                      className="w-10 h-10 rounded-full object-cover border"
                    />
                    <div className="flex flex-col">
                      <span className="font-semibold text-gray-800">
                        {r.user.username}
                      </span>
                      <span className="text-sm text-gray-500 flex items-center gap-1">
                        <RenderStars rating={Number(r.rating)} />
                      </span>
                    </div>
                  </Link>

                  {/* Report abuse */}
                  <Tooltip>
                    <TooltipTrigger>
                      <button
                        className="p-2 rounded-full hover:bg-gray-200"
                        onClick={() => setReportModalOpen(true)}
                      >
                        <MoreHorizontal className="w-5 h-5 text-gray-400" />
                      </button>
                    </TooltipTrigger>
                    <TooltipContent>Report Abuse</TooltipContent>
                  </Tooltip>
                </div>

                {/* Updated at */}
                <div className="text-xs text-gray-400">
                  {new Date(r.updated_at).toLocaleDateString()}
                </div>

                {/* Comment */}
                <p className="text-gray-700 text-sm">{r.comment}</p>
              </div>
            ))}
          </div>

          {/* Pagination */}
          {totalPages > 1 && (
            <div className="flex justify-center mt-4 gap-2">
              <button
                onClick={() => handlePageChange(currentPage - 1, filterRating)}
                disabled={currentPage === 1}
                className="px-3 py-1 border rounded disabled:opacity-40"
              >
                Prev
              </button>
              {Array.from({ length: totalPages }, (_, i) => (
                <button
                  key={i}
                  onClick={() => handlePageChange(i + 1, filterRating)}
                  className={`px-3 py-1 border rounded ${
                    currentPage === i + 1
                      ? "bg-black text-white border-black"
                      : ""
                  }`}
                >
                  {i + 1}
                </button>
              ))}
              <button
                onClick={() => handlePageChange(currentPage + 1, filterRating)}
                disabled={currentPage === totalPages}
                className="px-3 py-1 border rounded disabled:opacity-40"
              >
                Next
              </button>
            </div>
          )}

          {/* Review Report Modal */}
          <ReviewReportModal
            open={reportModalOpen}
            onClose={() => setReportModalOpen(false)}
          />
        </>
      ) : (
        <div className="flex flex-col items-center justify-center py-12 px-4 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
          <StarOff className="w-16 h-16 mb-4 text-gray-400" />
          <p className="text-gray-500 text-center text-lg font-medium">
            Belum ada review untuk produk ini.
          </p>
        </div>
      )}
    </div>
  );
}