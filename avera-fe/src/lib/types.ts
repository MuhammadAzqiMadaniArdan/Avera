export interface Voucher {
  id: number;
  code: string;
  value: number;
  minSpend: number;
  validUntil: string;
}
