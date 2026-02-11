"use client";
import { useState } from "react";

import CheckoutHeader from "@/features/checkout/components/CheckoutHeader";
import DeliveryAddress from "@/features/checkout/components/DeliveryAddress";
import ProductCardByStore from "@/features/checkout/components/ProductCardByStore";
import PaymentMethod from "@/features/checkout/components/PaymentMethod";
import PaymentMethodModal from "@/features/checkout/components/PaymentMethodModal";
import OrderSummary from "@/features/checkout/components/OrderSummary";
import { useCheckout } from "@/features/checkout/hooks";

export default function CheckoutPage() {
  const { checkout,setCheckout, loading, handlePlaceOrder, selectAddress, handleUpdatePaymentMethod,selectShipment } = useCheckout();

  const paymentMethod = useState<"cod" | "midtrans">("cod");
  const [openPayment, setOpenPayment] = useState(false);
 
  return (
    <div className="min-h-screen min-w-full bg-gray-100">
      <CheckoutHeader />

      {loading ? (
        <div className="flex justify-center items-center bg-white p-4 rounded-md w-full h-screen">
          <p>Loading....</p>
        </div>
      ) : !checkout ? (
        <div className="flex justify-center items-center bg-white p-4 rounded-md w-full h-screen">
          <p>Tidak ada checkout</p>
        </div>
      ) : (
        <div className="max-w-6xl mx-auto p-4 space-y-4">
          <DeliveryAddress
            user_address={checkout?.user_address}
            onSelect={selectAddress}
          />

          {/* Produk per Store */}
          <ProductCardByStore stores={checkout?.stores} selectShipment={selectShipment} />

          <PaymentMethod
            method={checkout?.payment_method ?? paymentMethod}
            onChange={() => setOpenPayment(true)}
          />

          <PaymentMethodModal
            open={openPayment}
            value={checkout?.payment_method ?? paymentMethod}
            onChange={(v) => setCheckout({...checkout,payment_method:v})}
            onClose={() => setOpenPayment(false)}
            onSubmit={handleUpdatePaymentMethod}
          />
          
          <OrderSummary checkout={checkout} onPlaceOrder={handlePlaceOrder} />
        </div>
      )}
    </div>
  );
}
