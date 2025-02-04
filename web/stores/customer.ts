import type { CustomerInterface } from "~/interfaces/customer";

export const useCustomerStore = defineStore("videos", () => {
  const customer = ref<CustomerInterface | null>(null);

  const setCustomer = (data: CustomerInterface) => {
    customer.value = data;
  };

  return { setCustomer, customer };
});
