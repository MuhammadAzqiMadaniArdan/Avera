export function formatSold(value: number): string {
  if (value < 1_000) return value.toString();

  if (value < 1_000_000) {
    return `${Math.floor(value / 1_000)}rb`;
  }

  if (value < 1_000_000_000) {
    const num = value / 1_000_000;
    return `${num % 1 === 0 ? num : num.toFixed(1)}jt`;
  }

  const num = value / 1_000_000_000;
  return `${num % 1 === 0 ? num : num.toFixed(1)}M`;
}
