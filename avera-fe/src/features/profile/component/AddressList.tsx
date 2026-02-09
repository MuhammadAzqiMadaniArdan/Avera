import { UserAddress } from "../types";
import { AddressItem } from "./AddressItem";

interface Props {
  addresses: UserAddress[];
  onEdit: (a: UserAddress) => void;
  onDelete: (a: UserAddress) => void;
}


export function AddressList({ addresses, onEdit, onDelete }: Props) {
  return (
    <div className="space-y-4">
      {addresses.map((a) => (
        <AddressItem
          key={a.id}
          address={a}
          onEdit={() => onEdit(a)}
          onDelete={() => onDelete(a)}
        />
      ))}
    </div>
  );
}
