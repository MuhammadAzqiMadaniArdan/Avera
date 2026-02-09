import { ShieldCheck, ShoppingCart, Star, Truck } from "lucide-react";
import { ProductDetail } from "../../types";
import { CourierSla } from "@/features/shipment/type";

export default function ProductInfo({
  product,
  qty,
  setQty,
  color,
  setColor,
  courierSla,
  handleAddToCart,
  handleBuyNow
}: {
  product: ProductDetail;
  qty: number;
  setQty: React.Dispatch<React.SetStateAction<number>>;
  color: string;
  courierSla: CourierSla;
  setColor: React.Dispatch<React.SetStateAction<string>>;
  handleAddToCart: () => Promise<void>;
  handleBuyNow: () => Promise<void>;
}) {
  return (
    <div className="max-w-[460px] flex flex-col h-[420px]">
      {/* Make content scrollable if overflow */}
      <div className="flex-1 overflow-y-auto pr-2 space-y-4">
        {/* TITLE */}
        <h1 className="text-2xl font-semibold leading-snug">{product.name}</h1>

        {/* RATING */}
        <div className="flex items-center gap-2 text-sm text-gray-500">
          <span className="text-yellow-500 font-semibold">
            {product.rating}
          </span>
          <div className="flex">
            {[1, 2, 3, 4, 5].map((i) => (
              <Star
                key={i}
                className={`h-4 w-4 ${
                  i <= Math.floor(product.rating)
                    ? "fill-yellow-400 text-yellow-400"
                    : "text-gray-300"
                }`}
              />
            ))}
          </div>
          <span className="ml-1">
            {product.reviews} ratings • {product.sold} sold
          </span>
        </div>

        {/* PRICE */}
        <div className="text-3xl font-semibold text-red-600">
          Rp {product.price.toLocaleString("id-ID")}
        </div>

        {/* SHIPPING & GUARANTEE */}
        <div className="border rounded-lg px-3 py-2 text-sm space-y-2 bg-gray-50">
          <div className="flex gap-2">
            <Truck className="h-5 w-5 text-gray-700" />
            <div>
              <p className="font-medium text-gray-800">Shipping</p>
              <p className="text-gray-600">Guaranteed 27 – 29 Jan</p>
              <p className="text-green-600 font-medium">
                Voucher up to Rp10.000 if late
              </p>
            </div>
          </div>
          <div className="flex gap-2 items-center">
            <ShieldCheck className="h-5 w-5 text-gray-700" />
            <p className="text-gray-600">Free Return · COD</p>
          </div>
        </div>

        {/* QUANTITY */}
        <div className="flex items-center gap-4 text-sm mt-2">
          <div className="flex items-center border rounded-md">
            <button
              onClick={() => setQty((q) => Math.max(1, q - 1))}
              className="px-3 py-2 text-lg font-medium hover:bg-gray-100"
            >
              -
            </button>
            <span className="px-4 border-x text-lg">{qty}</span>
            <button
              onClick={() => setQty((q) => Math.min(q + 1, product.stock))}
              className="px-3 py-2 text-lg font-medium hover:bg-gray-100"
            >
              +
            </button>
          </div>
          <span className="text-green-600 font-semibold">IN STOCK</span>
        </div>
      </div>

      {/* ACTION BUTTONS — sticky bottom */}
      <div className="mt-auto flex gap-3 pt-3">
        {/* Add to Cart */}
        <button
          onClick={handleAddToCart}
          className="flex-1 h-12 flex items-center justify-center gap-2 bg-primary/20 text-primary outline outline-1 outline-primary rounded-lg font-semibold hover:bg-primary/15 transition-colors"
        >
          <ShoppingCart className="h-5 w-5" />
          Add to Cart
        </button>

        <button
          onClick={handleBuyNow}
          className="flex-1 h-12 flex items-center justify-center gap-2 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition-colors"
        >
          <span>Buy Now</span>
        </button>
      </div>
    </div>
  );
}
