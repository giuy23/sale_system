export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at: string;
}

export type UserType = {
  id: number;
  name: string;
  sur_name: string;
  email: string;
  document_number: number;
  cell_phone: number | null;
  role_id: number;

  password: string,
  state?: boolean;
  role?: string;
  image?: string;
}

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
  id: number,
  name: string,
  document_number: number | null,
  name_company: string,
  cellphone: number | null,
  image?: string,
}

export type Product = {
  id: number,
  name: string,
  description: string | null,
  purchase_price: number,
  sale_price: number,
  bar_code: string,
  quantity: number,
  minimum_quantity: number,
  state?: boolean,
  sub_category_id: number,
  provider_id: number,

  images?: string[]
  image?: string,
}

export type ProductToSell = {
  id: number,
  name: string,
  sale_price: number,
  quantity: number,
  image?: string,

  quantitySell?: number,
  discount?: number,
  subTotal?: number
}

export type DailyCash = {
  id: number,
  start_money: number,
  final_money: number,
  profit: number,
  state: boolean,
  user_name: string,
  user_id: number,

  created_at: string,
  updated_at: string,
}

export type Client = {
  id: number,
  full_name: string,
  document_number: number,
  cell_phone: number | null,
  state?: boolean,
}

export type Expense = {
  id: number,
  amount: number,
  description: string,
  daily_cash_id: number,
}

export type DailyCashCreate = Pick<DailyCash, 'id' | 'start_money' >

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>
> = T & {
  auth: {
    user: User;
  };
};
