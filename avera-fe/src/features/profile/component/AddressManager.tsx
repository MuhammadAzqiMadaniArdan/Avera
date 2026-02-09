import { AddressList } from "./AddressList";
import { AddressFormDialog } from "./AddressFormDialog";
import { AddressDeleteDialog } from "./AddressDeleteDialog";
import { Button } from "@/components/ui/button";
import { useAddress } from "../hooks";
import { useState } from "react";

export default function AddressManager() {
  const {
    addresses,
    loading,
    formOpen,
    setFormOpen,
    editing,
    setEditing,
    deleting,
    setDeleting,
    save,
    remove,
  } = useAddress();

  if (loading) {
    return (
      <div className="flex justify-center items-center w-full h-screen">
        <p className="text-lg font-medium">Loading...</p>
      </div>
    );
  }
  return (
    <div className="space-y-4">
      <div className="flex justify-between">
        <h2 className="font-semibold text-lg">My Addresses</h2>
        <Button
          onClick={() => {
            setEditing(null);
            setFormOpen(true);
          }}
        >
          Add Address
        </Button>
      </div>
      <AddressList
        addresses={addresses}
        onEdit={(a) => {
          setEditing(a);
          setFormOpen(true);
        }}
        onDelete={(a) => setDeleting(a)}
      />

      <AddressFormDialog
        open={formOpen}
        initial={editing}
        onClose={() => setFormOpen(false)}
        onSubmit={async (data) => {
          await save(data);
          setFormOpen(false); 
        }}
      />

      <AddressDeleteDialog
        open={!!deleting}
        onClose={() => setDeleting(null)}
        onConfirm={remove}
      />
    </div>
  );
}
