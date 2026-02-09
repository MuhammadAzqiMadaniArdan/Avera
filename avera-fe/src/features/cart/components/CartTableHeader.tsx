interface Props {
  checked: boolean;
  onToggle(): void;
}

export function CartTableHeader({ checked, onToggle }: Props) {
  return (
        <div className="hidden md:grid grid-cols-[0.5fr_3fr_1fr_1fr_1fr_2fr] gap-4 p-4 bg-white border-b font-medium text-gray-600">
          <input
            type="checkbox"
            checked={selectedIds.length === filteredCart.length}
            onChange={toggleSelectAll}
          />
          <span>Product</span>
          <span>Unit Price</span>
          <span>Quantity</span>
          <span>Total Price</span>
          <span>Actions</span>
        </div>
  );
}
