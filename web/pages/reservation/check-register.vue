<template>
  <div class="flex items-center justify-center h-screen">
    <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
      <UCard style="width: 600px">
        <template #header>
          <p
            class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
          >
            Check your register
          </p>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Insert your CPF to check your register, please.
          </p>
        </template>
        <UFormGroup label="CPF" name="cpf">
          <UInput
            v-model="state.cpf"
            @update:modelValue="(value) => (state.cpf = formatCPF(value))"
            inputmode="numeric"
            maxlength="14"
          />
        </UFormGroup>

        <template #footer>
          <UButton type="submit" color="red" :loading="loading">Check</UButton>
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
  cpf: z
    .string()
    .regex(/^\d{3}\.\d{3}\.\d{3}-\d{2}$/, { message: "CPF inválido" })
    .transform((cpf) => cpf.replace(/\D/g, "")),
});

type Schema = z.output<typeof schema>;

const state = reactive({
  cpf: "",
});

const router = useRouter();
const config = useRuntimeConfig();
const { successMessage, errorMessage } = useMessage();

const customerStore = useCustomerStore();

async function onSubmit(event: FormSubmitEvent<Schema>) {
  loading.value = true;

  try {
    const response: any = await $fetch(
      `${config.public.API_URL}/customers/check-record`,
      {
        method: "POST",
        body: event.data,
      }
    );

    customerStore.setCustomer(response.data);

    router.push("/reservation/check-code");
    successMessage(response.message);
  } catch (error: any) {
    const message = extractError(error);

    if (message.key === "not_found") {
      message.text = "Customer not found. Please register first.";
      router.push("/reservation/register-customer");
    }

    errorMessage(message.text);
  }

  loading.value = false;
}
</script>
