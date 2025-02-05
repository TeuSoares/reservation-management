<template>
  <div class="flex items-center justify-center h-screen">
    <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
      <UCard style="width: 600px">
        <template #header>
          <p
            class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
          >
            Register user
          </p>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Make your register, please.
          </p>
        </template>

        <div class="flex flex-col gap-3">
          <UFormGroup label="Name" name="name">
            <UInput v-model="state.name" />
          </UFormGroup>

          <UFormGroup label="E-mail" name="email">
            <UInput v-model="state.email" type="email" />
          </UFormGroup>

          <UFormGroup label="CPF" name="cpf">
            <UInput
              v-model="state.cpf"
              @update:modelValue="(value) => (state.cpf = formatCPF(value))"
              inputmode="numeric"
              maxlength="14"
            />
          </UFormGroup>

          <UFormGroup label="Phone" name="phone">
            <UInput
              v-model="state.phone"
              @update:modelValue="
                (value) => (state.phone = formatPhoneNumber(value))
              "
              inputmode="numeric"
              maxlength="15"
            />
          </UFormGroup>

          <UFormGroup label="Birth date" name="birth_date">
            <UInput v-model="state.birth_date" type="date" />
          </UFormGroup>
        </div>

        <template #footer>
          <UButton type="submit" color="red">Register</UButton>
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
  name: z
    .string()
    .max(50)
    .regex(
      new RegExp("[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$"),
      "The field name must contain only letters."
    )
    .transform((name) => {
      return name
        .trim()
        .split(" ")
        .map((word) => {
          return word[0].toLocaleUpperCase().concat(word.substring(1));
        })
        .join(" ");
    }),
  cpf: z
    .string()
    .regex(/^\d{3}\.\d{3}\.\d{3}-\d{2}$/, { message: "CPF is invalid" })
    .transform((cpf) => cpf.replace(/\D/g, "")),
  email: z.string().email().max(50),
  phone: z
    .string()
    .regex(/^\(\d{2}\) \d{5}-\d{4}$/, { message: "Phone is invalid" })
    .transform((phone) => phone.replace(/\D/g, "")),
  birth_date: z
    .string()
    .regex(/^\d{4}-\d{2}-\d{2}$/, { message: "Birth date is invalid" })
    .refine((value) => value <= new Date().toISOString().split("T")[0], {
      message: "Date of birth cannot be later than today",
    }),
});

type Schema = z.output<typeof schema>;

const state = reactive({
  cpf: "",
  name: "",
  email: "",
  phone: "",
  birth_date: "",
});

const router = useRouter();
const config = useRuntimeConfig();
const { successMessage, errorMessage } = useMessage();

const customerStore = useCustomerStore();

async function onSubmit(event: FormSubmitEvent<Schema>) {
  loading.value = true;

  try {
    const response: any = await $fetch(`${config.public.API_URL}/customers`, {
      method: "POST",
      body: event.data,
    });

    customerStore.setCustomer(response.data);

    router.push("/reservation/check-code");
    successMessage(response.message);
  } catch (error: any) {
    const message = extractError(error);
    errorMessage(message.text);
  }

  loading.value = false;
}
</script>
