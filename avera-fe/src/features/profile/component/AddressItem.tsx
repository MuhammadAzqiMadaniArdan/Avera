import { Button } from "@/components/ui/button";
import { UserAddress } from "../types";

interface Props {
  address: UserAddress;
  onEdit: () => void;
  onDelete: () => void;
}

export function AddressItem({ address, onEdit, onDelete }: Props) {
  return (
    <div className="bg-white p-4 rounded-lg shadow">
      <div className="flex justify-between">
        <div>
          <p className="font-semibold">{address.recipient_name}</p>
          <p className="text-sm text-gray-500">{address.recipient_phone}</p>
        </div>
        <div className="flex gap-2">
          <Button size="sm" variant="link" onClick={onEdit}>Edit</Button>
          <Button size="sm" variant="link" className="text-red-600" onClick={onDelete}>
            Delete
          </Button>
        </div>
      </div>

      <p className="text-sm text-gray-600 mt-2">
        {address.address}, {address.village_name}, {address.district_name},
        {address.city_name}, {address.province_name}
      </p>

      <div className="mt-2 flex gap-2">
        <span className="px-2 py-1 text-xs rounded bg-gray-100">
          {address.label}
        </span>
        {address.is_default && (
          <span className="px-2 py-1 text-xs rounded bg-green-200 text-green-800">
            Default
          </span>
        )}
      </div>
    </div>
  );
}
