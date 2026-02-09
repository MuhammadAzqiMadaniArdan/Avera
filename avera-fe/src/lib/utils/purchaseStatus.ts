export function getStatusLabel(status: string) {
  switch (status) {
    case "unpaid":
      return "To Pay";
    case "paid":
      return "Processing";
    case "shipped":
      return "To Receive";
    case "completed":
      return "Completed";
    case "cancelled":
      return "Cancelled";
    case "cod_pending":
      return "Pending";
    default:
      return status;
  }
}

export function getStatusColor(status: string) {
  switch (status) {
    case "unpaid":
      return "text-orange-600";
    case "paid":
      return "text-blue-600";
    case "shipped":
      return "text-purple-600";
    case "completed":
      return "text-green-600";
    case "cancelled":
      return "text-red-600";
    default:
      return "text-gray-600";
  }
}
