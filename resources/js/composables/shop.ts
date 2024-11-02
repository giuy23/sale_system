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
const descriptionDebt = ref<string>();
const saleType = ref<number>(1);
const client = ref(0);
const loading = ref(false);

const createCashSale = async () => {
  productsSold.value = [];
  transformedProducts();
  loading.value = true;
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
    loading.value = false;
  }
};

const createCreditSale = async () => {
  productsSold.value = [];
  transformedProducts();
  try {
    loading.value = true;
    const { data } = await axios({
      method: "POST",
      url: route("shop.createSale"),
      data: {
        saleType: saleType.value,
        products: productsSold.value,
        client_id: client.value,
        customerPayment: customerPayment.value,
        description: descriptionDebt.value,
      },
    });
    return { success: true, data };
  } catch (error) {
    return { success: false };
  } finally {
    loading.value = false;
  }
};

const viewReceipt = (saleId: number | null) => {
  if (saleId === null) return;
  const pdfWindow = window.open(route("sale.detailSale", saleId), "_blank");
  pdfWindow!.onload = function () {
    pdfWindow!.print();
  };
};
// DEVOLVER LA URL CUANDO SE HIZO BIEN

const resetFormValues = () => {
  products.value = [];
  client.value = 1;
  saleType.value = 1;
  customerPayment.value = 0;
  descriptionDebt.value = "";
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

  const deleteProductToForm = (id: number) => {
    const findIndex = products.value.findIndex((el) => el.id === id);
    if (findIndex !== -1) {
      products.value.splice(findIndex, 1);
    }
  };

  const saveSale = async () => {
    const response = { success: true, msg: "", saleId: null };

    if (saleType.value === 1) {
      const { success, data } = await createCashSale();
      !success
        ? (response.success = false)
        : ((response.msg = data.message), (response.saleId = data.sale_id));
    } else {
      if (client.value === 1) {
        response.success = false;
        response.msg = "Una deuda debe tener a un cliente existente";
        return response;
      }
      const { success, data } = await createCreditSale();
      !success
        ? (response.success = false)
        : ((response.msg = data.message), (response.saleId = data.sale_id));
    }
    return response;
  };

  return {
    products,
    saleType,
    customerPayment,
    client,
    loading,

    subTotal,
    total,
    backMoney,
    debtCustomer,
    descriptionDebt,
    saveSale,
    deleteProductToForm,
    resetFormValues,
    viewReceipt,
  };
}
