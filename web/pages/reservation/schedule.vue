<template>
  <div class="flex items-center justify-center h-screen">
    <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
      <UCard style="width: 600px">
        <template #header>
          <p
            class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
          >
            Schedule your reservation
          </p>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Please choose the date of your reservation
          </p>
        </template>

        <div class="flex flex-col gap-6">
          <div class="flex flex-col gap-2">
            <div class="flex items-center justify-between">
              <h1 class="text-base font-bold text-main">Your data</h1>
              <UButton color="red" size="xs" to="/reservation/register-customer"
                >Edit</UButton
              >
            </div>

            <p class="text-sm"><strong>Name:</strong> {{ customer?.name }}</p>
            <p class="text-sm">
              <strong>CPF: </strong>
              <span v-format-cpf>{{ customer?.cpf }}</span>
            </p>
            <p class="text-sm">
              <strong>Phone: </strong
              ><span v-format-phone>{{ customer?.phone }}</span>
            </p>
            <p class="text-sm">
              <strong>E-mail:</strong> {{ customer?.email }}
            </p>
            <p class="text-sm">
              <strong>Birthday: </strong
              ><span v-format-date-br>{{ customer?.birth_date }}</span>
            </p>
          </div>

          <UDivider />

          <div class="flex flex-col gap-3">
            <h1 class="text-base font-bold text-main">Reservation date</h1>

            <UFormGroup label="Choose date" name="booking_date">
              <UInput v-model="state.booking_date" type="datetime-local" />
            </UFormGroup>

            <UFormGroup label="Number of people" name="number_people">
              <UInput v-model="state.number_people" type="number" />
            </UFormGroup>
          </div>
        </div>

        <template #footer>
          <UButton type="submit" color="red" :loading="loading"
            >Confirm</UButton
          >
        </template>
      </UCard>
    </UForm>
  </div>
</template>

<script setup lang="ts">
import { z } from "zod";
import type { FormSubmitEvent } from "#ui/types";

const loading = ref(false);

const schema = z.object({
  booking_date: z.string().refine(
    (value) => {
      const date = new Date(value);
      return !isNaN(date.getTime());
    },
    { message: "Invalid date format" }
  ),
  number_people: z.number(),
});

type Schema = z.output<typeof schema>;

const customerStore = useCustomerStore();
const { customer, code } = storeToRefs(customerStore);

const router = useRouter();
const config = useRuntimeConfig();
const { successMessage, errorMessage } = useMessage();

const state = reactive({
  booking_date: "",
  number_people: 0,
});

async function onSubmit(event: FormSubmitEvent<Schema>) {
  loading.value = true;

  try {
    const response: any = await $fetch(
      `${config.public.API_URL}/reservations`,
      {
        method: "POST",
        body: {
          ...event.data,
          customer_id: customer.value!.id,
        },
        headers: {
          "X-Verification-Code": code.value!,
        },
      }
    );

    router.push("/reservation/thanks");
    customerStore.setCustomer(null);
    successMessage(response.message);
  } catch (error: any) {
    const message = extractError(error);
    errorMessage(message.text);
  }

  loading.value = false;
}
</script>
