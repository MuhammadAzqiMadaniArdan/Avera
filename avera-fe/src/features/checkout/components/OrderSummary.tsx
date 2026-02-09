import { Button } from "@/components/ui/button";
import { Checkout } from "../types";
import { formatCurrency } from "@/lib/utils/formatCurrency";

interface Props {
  checkout: Checkout;
  onPlaceOrder: () => void;
}

export default function OrderSummary({ checkout, onPlaceOrder }: Props) {
  return (
    <div className="bg-white rounded-md p-4 space-y-2">
      <div className="flex justify-between text-sm">
        <span>Merchandise Subtotal</span>
        <span>{formatCurrency(checkout.subtotal)}</span>
      </div>

      <div className="flex justify-between text-sm">
        <span>Shipping Subtotal</span>
        <span>{formatCurrency(checkout.shipping_cost)}</span>
      </div>

      <div className="flex justify-between font-semibold text-lg border-t pt-2">
        <span>Total Payment</span>
        <span>{formatCurrency(checkout.total_price)}</span>
      </div>

      <div className="flex justify-end mt-4">
        <Button size="lg" onClick={onPlaceOrder}>
          Place Order
        </Button>
      </div>
    </div>
  );
}
