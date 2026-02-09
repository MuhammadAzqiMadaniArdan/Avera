"use client";

import { useParams, useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import { Button } from "@/components/ui/button";
import { submitReview } from "@/features/reviews/services";
import { notify } from "@/lib/toast/notify";
import { Navbar } from "@/components/Navbar";
import { ReviewBase } from "@/features/reviews/types";
import { getOrderItemDetail } from "@/features/purchase/services";

export default function ReviewPage() {
  const { orderItemId } = useParams();
  const router = useRouter();

  const [loading, setLoading] = useState(true);
  const [existingReview, setExistingReview] = useState<ReviewBase | null>(null);

  const [rating, setRating] = useState(5);
  const [comment, setComment] = useState("");

  /* ===== CHECK REVIEW EXIST ===== */
  useEffect(() => {
    (async () => {
      try {
        const res = await getOrderItemDetail(orderItemId as string);

        if (res.data?.review) {
          setExistingReview(res.data.review);
        }
      } catch {
        notify.error("Gagal memuat data review");
        router.back();
      } finally {
        setLoading(false);
      }
    })();
  }, [orderItemId, router]);

  /* ===== SUBMIT ===== */
  const handleSubmit = async () => {
    try {
      await submitReview({
        order_item_id: orderItemId as string,
        rating,
        comment,
      });

      notify.success("Review berhasil dikirim");
      router.back();
    } catch {
      notify.error("Review sudah pernah dikirim");
    }
  };

  /* ===== LOADING ===== */
  if (loading) {
    return (
      <>
        <Navbar />
        <div className="flex justify-center py-20 text-gray-500">
          Loading review...
        </div>
      </>
    );
  }

  return (
    <>
      <Navbar />

      <div className="max-w-xl mx-auto px-4 py-8">
        <div className="bg-white rounded-xl shadow p-6 space-y-6">
          <h1 className="text-xl font-semibold">
            {existingReview ? "Your Review" : "Leave a Review"}
          </h1>

          {/* ===== EXISTING REVIEW ===== */}
          {existingReview ? (
            <ReviewedBox review={existingReview} />
          ) : (
            <>
              {/* ===== RATING ===== */}
              <div>
                <label className="block text-sm font-medium mb-1">
                  Rating
                </label>

                <div className="flex gap-2">
                  {[5, 4, 3, 2, 1].map((r) => (
                    <button
                      key={r}
                      onClick={() => setRating(r)}
                      className={`px-3 py-1 rounded border text-sm ${
                        rating === r
                          ? "bg-black text-white"
                          : "bg-white"
                      }`}
                    >
                      {r} ★
                    </button>
                  ))}
                </div>
              </div>

              {/* ===== COMMENT ===== */}
              <div>
                <label className="block text-sm font-medium mb-1">
                  Comment
                </label>

                <textarea
                  value={comment}
                  onChange={(e) => setComment(e.target.value)}
                  placeholder="Share your experience..."
                  className="w-full border rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                  rows={4}
                />
              </div>

              {/* ===== ACTION ===== */}
              <div className="flex justify-end">
                <Button onClick={handleSubmit}>
                  Submit Review
                </Button>
              </div>
            </>
          )}
        </div>
      </div>
    </>
  );
}

/* ================= REVIEWED BOX ================= */

function ReviewedBox({ review }: { review: ReviewBase }) {
  return (
    <div className="border rounded-lg bg-gray-50 p-4 space-y-2">
      <div className="flex items-center gap-2">
        <span className="text-yellow-500 font-semibold">
          {"★".repeat(review.rating)}
        </span>
        <span className="text-sm text-gray-500">
          {review.rating}/5
        </span>
      </div>

      <p className="text-sm text-gray-700 leading-relaxed">
        {review.comment}
      </p>

      <p className="text-xs text-gray-400">
        Reviewed on{" "}
        {new Date(review.updated_at).toLocaleDateString("id-ID")}
      </p>
    </div>
  );
}
