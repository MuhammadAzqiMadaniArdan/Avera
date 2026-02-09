"use client";

import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { UserAddress } from "@/features/profile/types";

interface Props {
  open: boolean;
  selectedAddressId: string | null | undefined;
  addresses: UserAddress[] | null;
  onSelect: (addressId: string) => Promise<void>;
  onClose: () => void;
  onEdit: (a: UserAddress) => void;
  onCreate: () => void;
  loading: boolean;
}

export default function AddressModal({
  open,
  selectedAddressId,
  addresses,
  onSelect,
  onEdit,
  onClose,
  onCreate,
  loading,
}: Props) {
  const safeAddresses = addresses || [];
  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent className="max-w-lg">
        <DialogHeader>
          <DialogTitle>My Address</DialogTitle>
          {loading && (
            <p className="text-sm text-gray-500 mt-1">Loading addresses...</p>
          )}
        </DialogHeader>

        <div className="space-y-3 mt-2">
          {safeAddresses.length === 0 && !loading ? (
            <p className="text-sm text-gray-500">
              You don’t have any saved address
            </p>
          ) : (
            <div className="space-y-2 mb-2">
              {safeAddresses.map((addr) => (
                <div
                  key={addr.id}
                  className="border rounded p-3 hover:bg-gray-50 relative"
                >
                  <button
                    className={`w-full text-left ${
                      addr.id === selectedAddressId
                        ? "cursor-not-allowed opacity-60"
                        : "hover:bg-gray-50"
                    }`}
                    onClick={async () => {
                      if (addr.id === selectedAddressId) return;
                      await onSelect(addr.id);
                      onClose();
                    }}
                    disabled={loading || addr.id === selectedAddressId}
                  >
                    <p className="text-sm font-medium">
                      {addr.recipient_name} ({addr.recipient_phone})
                    </p>
                    <p className="text-xs text-gray-600">
                      {addr.address}, {addr.city_name}, {addr.province_name}
                    </p>
                    <div className="flex gap-1 pt-2">
                      {addr.id === selectedAddressId && (
                        <span className="inline-block mt-1 text-xs text-primary border border-primary px-2 py-0.5 rounded">
                          Selected
                        </span>
                      )}
                      {addr.is_default && (
                          <span className="inline-block mt-1 text-xs text-primary border border-primary px-2 py-0.5 rounded">
                          Default
                        </span>
                      )}
                    </div>
                  </button>

                  <Button
                    size="sm"
                    variant="link"
                    className="absolute top-2 right-2"
                    onClick={() => onEdit(addr)}
                    disabled={loading} // disable saat loading
                  >
                    Edit
                  </Button>
                </div>
              ))}
            </div>
          )}

          <Button
            variant="outline"
            className="w-full"
            onClick={onCreate}
            disabled={loading}
          >
            + Add New Address
          </Button>
        </div>
      </DialogContent>
    </Dialog>
  );
}
