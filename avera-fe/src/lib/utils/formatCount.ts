export function formatCount(count: number): string {
  if (!count) return '0'; 
  if (count < 1000) return count.toString();
  if (count < 1_000_000) return `${(count / 1000).toFixed(0)}k`;
  if (count < 1_000_000_000) return `${(count / 1_000_000).toFixed(1)}M`;
  return `${(count / 1_000_000_000).toFixed(1)}B`;
}
