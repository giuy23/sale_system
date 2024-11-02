export interface User {
  id: number;
  name: string;
  sur_name: string;
  email: string;
  email_verified_at: string;
  role_id: number;
  document_number: number;
  cell_phone: number | null;
}

export type UserType = Pick<
  User,
  | "id"
  | "name"
  | "sur_name"
  | "email"
  | "role_id"
  | "document_number"
  | "cell_phone"
> & {
  password: string;
  state?: boolean;
  role?: string;
  image?: string;
};

export type UserUpdate = Pick<
  User,
  "id" | "name" | "sur_name" | "email" | "document_number" | "cell_phone"
>;

export type MetaLinks = {
  current_page: number;
  from: number;
  last_page: number;
  links: object;
  path: string;
  per_page: number;
  to: number;
  total: number;
};

export type GetDataWithParams = {
  data: Array[];
  links: Object;
  meta: MetaLinks;
};

export type Category = {
  id: number;
  name: string;
  description?: string;
};

export type SubCategory = {
  id: number;
  name: string;
  category_id: number;
  category?: Category;
};

export type Provider = {
  id: number;
  name: string;
  document_number: number | null;
  name_company: string;
  cellphone: number | null;
  image?: string;
};

export type Product = {
  id: number;
  name: string;
  description: string | null;
  purchase_price: number;
  sale_price: number;
  bar_code: string;
  quantity: number;
  minimum_quantity: number;
  state?: boolean;
  sub_category_id: number;
  provider_id: number;

  images?: string[];
  image?: File | string;
};

export type ProductToSell = {
  id: number;
  name: string;
  sale_price: number;
  quantity: number;
  image?: string;

  quantitySell?: number;
  discount?: number;
  subTotal?: number;
};

export type DailyCash = {
  id: number;
  start_money: number;
  final_money: number;
  profit: number;
  state: boolean;
  user_name: string;
  user_id: number;

  created_at: string;
  updated_at: string;
};

export type Client = {
  id: number;
  full_name: string;
  document_number: number | null;
  cell_phone: number | null;
  state?: boolean;
};

export type Expense = {
  id: number;
  amount: number;
  description: string;
  daily_cash_id: number;
  type: number;
};

export type Sale = {
  id: number;
  created_at: Date;
  // sub_total: number,
  discount_total: number;
  total: number;
  igv: number;
  state: number;
  user_name: string;
  client_name: string;
};

export type SaleDetails = {
  id: number;
  sale_id: number;
  product_id: number;
  price: number;
  quantity: number;
  discount: number;
  total: number;
  created_at: Date;

  product_name: string;
  quantitySell?: number;
};

export type Enterprise = {
  id: number;
  name: string;
  name_comercial: string;
  description: string;
  cell_phone: number;
  address: string;
  RUC: number;

  image?: string;
};

export type CreditSaleClient = {
  id: number;
  amount_payable: number;
  remaining_amount: number;
  description: string | null;
  sale_id: number;
  created_at: Date;
};

export type ClientTotalDebt = {
  id: number;
  full_name: string;
  total_debt: number;
};

export type SearchByDate = {
  start_date: string,
  end_date: string,
  minimum_amount?: number | null;
  maximum_amount?: number | null;

}

export type DailyCashCreate = Pick<DailyCash, "id" | "start_money">;
export type DailyCashCompare = Pick<
  DailyCash,
  "id" | "final_money" | "created_at"
>;
export type ClientToSell = Pick<Client, "id" | "full_name" | "document_number">;
export type CreateExpense = Pick<Expense, "id" | "type">;
export type BestProductSold = Pick<Product, "id", "name"> & {
  sales_count: number;
};
export type ProductRestock = Pick<Product, "id" | "name" | "quantity">;

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>
> = T & {
  auth: {
    user: User;
    image: string;
  };
};
