import type { CustomerInterface } from "~/interfaces/customer";

export const useCustomerStore = defineStore("videos", () => {
  const customer = ref<CustomerInterface | null>(null);
  const code = ref<string | null>(null);

  const setCustomer = (data: CustomerInterface) => {
    customer.value = data;
  };

  const setCode = (data: string) => {
    code.value = data;
  };

  return { setCustomer, customer, setCode, code };
});
