export function estimateDelivery(
  minDays?: number | null,
  maxDays?: number | null
): string {
  if (!minDays && !maxDays) return "-";

  const today = new Date();

  const minDate = new Date(today);
  minDate.setDate(today.getDate() + (minDays ?? 0));

  const maxDate = new Date(today);
  maxDate.setDate(today.getDate() + (maxDays ?? minDays ?? 0));

  const options: Intl.DateTimeFormatOptions = {
    day: "numeric",
    month: "short",
  };

  const minText = minDate.toLocaleDateString("id-ID", options);
  const maxText = maxDate.toLocaleDateString("id-ID", options);

  return minText === maxText ? minText : `${minText} - ${maxText}`;
}
