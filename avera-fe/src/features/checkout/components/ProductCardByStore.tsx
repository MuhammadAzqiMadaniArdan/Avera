"use client";

import Image from "next/image";
import { Button } from "@/components/ui/button";
import { CheckoutStore } from "../types";
import { useState } from "react";
import ShippingModal from "./ShippingModal";
import { formatCurrency } from "@/lib/utils/formatCurrency";
import { estimateDelivery } from "@/lib/utils/estimateDelivery";

interface Props {
  stores: CheckoutStore[];
  selectShipment: (
    storeId: string,
    shipmentId: string,
    activeShipmentId: string
  ) => Promise<void>;
}


export default function ProductCardByStore({ stores,selectShipment }: Props) {
  const [openShipping, setOpenShipping] = useState<string | null>(null);

  return (
    <div className="flex flex-col gap-4 bg-red-400 space-y-10">
      {stores.map((store) => (
        <div
          key={store.store.slug}
          className="bg-white rounded-md p-4 space-y-3 overflow-x-auto"
        >
          <div className="flex justify-between items-center mb-2 w-full text-sm font-semibold text-gray-500 min-w-[600px]">
            <div className="min-w-0">Products Ordered</div>
            <div>Unit Price</div>
            <div>Amount</div>
            <div>Item Subtotal</div>
          </div>
          <div className="border-t  space-y-4 min-w-[600px]">
            <p className="font-medium py-3">{store.store.name}</p>
            {store.items.map((item) => (
              <div
                key={item.id}
                className="flex justify-between items-center gap-4 text-sm w-full min-w-0"
              >
                {/* Product Name + Image */}
                <div className="flex gap-3 min-w-0">
                  <Image
                    src={
                      item.product.primaryImage ??
                      "http://loremflickr.com/48/48"
                    }
                    alt={item.product.name}
                    width={48}
                    height={48}
                    className="shrink-0 rounded"
                  />
                  <p className="truncate min-w-0" title={item.product.name}>
                    {item.product.name.length > 45
                      ? item.product.name.slice(0, 45) + "..."
                      : item.product.name}
                  </p>
                </div>

                <p>{formatCurrency(item.price)}</p>

                <p>{item.quantity}</p>

                <p className="font-medium">{formatCurrency(item.subtotal)}</p>
              </div>
            ))}
            <div className="flex justify-between pt-3 items-center text-sm mt-4">
              <div>
                <p className="font-medium">
                  Shipping Option : {store.selected_shipment?.courier_name}
                </p>
                <p className="text-xs text-gray-500">
                  Estimated delivery{" "}
                  {estimateDelivery(
                    store.selected_shipment?.min_days,
                    store.selected_shipment?.max_days,
                  )}{" "}
                </p>
              </div>
              <div className="flex items-center gap-2">
                <Button
                  variant="link"
                  onClick={() => setOpenShipping(store.store.id)}
                >
                  Change
                </Button>
                <span className="font-medium">
                  {formatCurrency(store.shipping_cost)}
                </span>
              </div>
            </div>
            <ShippingModal
              open={openShipping === store.store.id}
              shipments={store?.shipments}
              onClose={() => setOpenShipping(null)}
              onConfirm={(shipmentId,activeShipmentId) =>
                selectShipment(store.store.id, shipmentId,activeShipmentId)
              }
            />
          </div>

        </div>
      ))}
    </div>
  );
}
