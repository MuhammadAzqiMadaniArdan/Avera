import { StoreDetail } from "@/features/cart/types";
import { formatCount } from "@/lib/utils/formatCount";
import Image from "next/image";
import Link from "next/link";

export default function StoreInfo({ store }: StoreDetail) {
  return (
    <section className="max-w-7xl mx-auto px-4 py-6">
      <div className="bg-white border rounded-xl p-6 grid grid-cols-2 md:grid-cols-5 gap-4 text-sm">
        <div className="col-span-2 flex gap-4">
          {store.logo ? (
            <Link
              href={`/store/${store.slug}`}
              className="relative w-14 h-14 rounded-full overflow-hidden"
            >
              <Image
                src={store.logo ?? "https://loremflickr.com/14/14"}
                alt={store.name}
                fill
                className="object-contain"
              />
            </Link>
          ) : (
            <div className="w-14 h-14 bg-gray-200 rounded-full" />
          )}
          <div>
            <p className="font-semibold">{store.name}</p>
            <p className="text-gray-500 text-xs">Active 1 hour ago</p>
          </div>
        </div>

        <div>
          <p className="font-medium">Ratings</p>
          <p>{formatCount(store.rating.count)}</p>
        </div>
        <div>
          <p className="font-medium">Response</p>
          <p>100%</p>
        </div>
        <div>
          <p className="font-medium">Products</p>
          <p>{formatCount(store.product_count)}</p>
        </div>
        <div>
          <p className="font-medium">Followers</p>
          <p>38,9k</p>
        </div>
      </div>
    </section>
  );
}