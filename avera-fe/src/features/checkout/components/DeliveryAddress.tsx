"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { Button } from "@/components/ui/button";
import AddressModal from "./AddressModal";
import { UserAddress } from "@/features/profile/types";
import { AddressFormDialog } from "@/features/profile/component/AddressFormDialog";
import { useAddress } from "@/features/profile/hooks";

interface Props {
  user_address: UserAddress | null;
  onSelect: (addressId: string) => Promise<void>;
}

export default function DeliveryAddress({ user_address, onSelect }: Props) {
  const router = useRouter();
  const [open, setOpen] = useState(false);
  const [submitting, setSubmitting] = useState(false);

  const {
    setEditing,
    setFormOpen,
    formOpen,
    editing,
    save,
    addresses: addressList,
    loading,
  } = useAddress();

  // ===== Jika tidak ada alamat user, wajib buka form =====
  useEffect(() => {
    if (!user_address && addressList.length === 0) {
      setEditing(null);
      setFormOpen(true);
    }
  }, [user_address, addressList, setEditing, setFormOpen]);

  const handleCancelForm = () => {
    if (submitting) return; //

    setFormOpen(false);

    if (!user_address && addressList.length === 0) {
      router.push("/cart");
    }
  };

  return (
    <>
      <div className="bg-white rounded-md p-4 flex justify-between items-start">
        <div>
          <h2 className="font-semibold text-sm mb-2">Delivery Address</h2>

          {!user_address ? (
            <p className="text-sm text-gray-500">
              No delivery address selected
            </p>
          ) : (
            <div className="space-y-2">
              <p className="text-sm font-medium">
                {user_address.recipient_name} ({user_address.recipient_phone})
              </p>
              <p className="text-sm text-gray-600 pb-3">
                {user_address.address}, {user_address.district_name},{" "}
                {user_address.city_name}, {user_address.province_name},{" "}
                {user_address.postal_code}
              </p>
              {user_address.is_default && (
                <span className="inline-block mt-2 text-xs text-primary border border-primary px-2 rounded">
                  Default
                </span>
              )}
            </div>
          )}
        </div>

        <Button variant="outline" onClick={() => setOpen(true)}>
          Change
        </Button>
      </div>

      <AddressModal
        open={open}
        selectedAddressId={user_address?.id}
        addresses={addressList}
        onSelect={onSelect}
        onEdit={(a) => {
          setEditing(a);
          setFormOpen(true);
        }}
        onCreate={() => {
          setEditing(null);
          setFormOpen(true);
        }}
        onClose={() => setOpen(false)}
        loading={loading}
      />

      <AddressFormDialog
        open={formOpen}
        initial={editing}
        onClose={handleCancelForm}
        onSubmit={async (data) => {
          try {
            setSubmitting(true);

            const saved = await save(data); // pastikan return address

            await onSelect(saved.id);

            setFormOpen(false); // ✅ CLOSE SETELAH SUKSES
          } finally {
            setSubmitting(false);
          }
        }}
      />
    </>
  );
}
