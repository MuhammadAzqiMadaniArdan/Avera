export interface User {
  id: string;
  username: string;
  avatar: string;
}

export interface ReviewBase {
  id: string;
  rating: number;
  comment: string;
  user: User;
  updated_at: string;
}

export interface PaginatedReviews {
  data: ReviewBase[];
  meta: {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
  };
}