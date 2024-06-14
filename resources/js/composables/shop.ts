import { ProductToSell } from "@/types";
import axios from "axios";
import { computed, ref } from "vue";

type ProductSold = {
  id: number;
  quantitySell: number;
  discount: number;
};

const products = ref<ProductToSell[]>([]);
const productsSold = ref<ProductSold[]>([]);
const customerPayment = ref<number>(0);
const saleType = ref<number>(1);
const client = ref(0);

const createCashSale = async () => {
  try {
    const { data } = await axios({
      method: "POST",
      url: route("shop.createSale"),
      data: {
        saleType: saleType.value,
        products: productsSold.value,
        client_id: client.value,
      },
    });
    return { success: true, data };
  } catch (error) {
    return { success: false };
  } finally {
  }
};

const createCreditSale = async () => {
  try {
    const { data } = await axios({
      method: "POST",
      url: route("shop.createSale"),
      data: {
        saleType: saleType.value,
        products: productsSold.value,
        client_id: client.value,
        customerPayment: customerPayment.value,
      },
    });
    return { success: true, data };
  } catch (error) {
    return { success: false };
  } finally {
  }
};

const transformedProducts = () => {
  products.value.map((product) => {
    let objectInsert: ProductSold = {
      id: product.id,
      quantitySell: product.quantitySell!,
      discount: product.discount ?? 0,
    };
    productsSold.value.push({ ...objectInsert });
  });
};

export function shop() {
  const subTotal = computed(() => (data: ProductToSell) => {
    let discount = data.discount || 0;
    let subTotal = (data.quantitySell! * (data.sale_price - discount)).toFixed(
      2
    );
    data.subTotal = Number(subTotal);
    return data.subTotal;
  });

  const total = computed(() => {
    return Number(
      products.value.reduce((accumulator, currentValue) => {
        return accumulator + currentValue.subTotal!;
      }, 0)
    ).toFixed(2);
  });

  const backMoney = computed(() => (total: number, customerPayment: number) => {
    if (customerPayment == 0) return total;
    return Number(customerPayment - total).toFixed(2);
  });

  const debtCustomer = computed(
    () => (total: number, customerPayment: number) => {
      if (customerPayment == 0) return total;
      return Number(total - customerPayment).toFixed(2);
    }
  );

  const saveSale = async () => {
    transformedProducts();
    const response = { success: true, msg: "" };

    if (saleType.value === 1) {
      const { success, data } = await createCashSale();
      !success ? (response.success = false) : response.msg = data;
    } else {
      if (client.value === 1) {
        response.success = false;
        response.msg = "Una deuda debe tener a un cliente existente";
        return response;
      }
      const { success, data } = await createCreditSale();
      !success ? (response.success = false) : response.msg = data;
    }
    return response;
  };

  return {
    products,
    saleType,
    customerPayment,
    client,

    subTotal,
    total,
    backMoney,
    debtCustomer,
    saveSale,
  };
}
