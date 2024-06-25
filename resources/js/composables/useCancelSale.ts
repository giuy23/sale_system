import { SaleDetails } from "@/types";
import axios from "axios";
import { computed, ref } from "vue";

const saleDetails = ref<SaleDetails[]>([]);

const confirmCancelSale = async () => {
  console.log("xd");

  try {
    const { data } = await axios({
      method: "POST",
      url: route("sale.confirmCancelSale"),
      data: {
        products: saleDetails.value,
        saleId: saleDetails.value[0].sale_id,
      },
    });

    return { success: true, data };
  } catch (error) {
    return { success: false };
  } finally {
  }
};

export function useCancelSale() {
  const subTotal = computed(() => (data: SaleDetails) => {
    const price = data.price - data.discount;
    return (data.total = data.quantity * price - data.quantitySell! * price).toFixed(2);
  });

  const amountToReturn = computed(() => {
    return Number(
      saleDetails.value.reduce((accumulator, currentValue) => {
        return accumulator + currentValue.total!;
      }, 0)
    ).toFixed(2);
  });

  return {
    saleDetails,
    subTotal,
    amountToReturn,

    confirmCancelSale,
  };
}
