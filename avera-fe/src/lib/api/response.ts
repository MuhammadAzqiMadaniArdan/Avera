export interface ApiResponse<T> {
  success: boolean;
  data?: T;
  message: string;
  meta?: {
    current_page?: number;
    per_page?: number;
    total?: number;
    last_page?: number;
  };
  code?: number;
  errors?: Record<string, string[]>;
}

