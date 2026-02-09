export function formatCurrency(value: number) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0, // hapus desimal
    maximumFractionDigits: 0, // hapus desimal
  }).format(value);
}
