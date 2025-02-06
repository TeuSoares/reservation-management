<template>
  <div class="flex items-center justify-center h-screen">
    <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
      <UCard style="width: 600px">
        <template #header>
          <p
            class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
          >
            Check your verification code
          </p>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Insert the verification code sent to your e-mail, please.
          </p>
        </template>

        <UFormGroup label="Verification code" name="code">
          <UInput v-model="state.code" inputmode="numeric" maxlength="6" />
        </UFormGroup>

        <UFormGroup name="id">
          <UInput v-model="state.id" type="hidden" />
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
  code: z.string().length(6, "The verification code must have 6 digits"),
  id: z.number().min(1, "The customer id is required"),
});

type Schema = z.output<typeof schema>;

const customerStore = useCustomerStore();
const { customer } = storeToRefs(customerStore);

const router = useRouter();
const config = useRuntimeConfig();
const { successMessage, errorMessage } = useMessage();

const state = reactive({
  code: "",
  id: customer.value!.id,
});

async function onSubmit(event: FormSubmitEvent<Schema>) {
  loading.value = true;

  try {
    const response: any = await $fetch(
      `${config.public.API_URL}/customers/check-code`,
      {
        method: "POST",
        body: event.data,
      }
    );

    customerStore.setCode(response.data.code);

    router.push("/reservation/schedule");
    successMessage(response.message);
  } catch (error: any) {
    const message = extractError(error);
    errorMessage(message.text);
  }

  loading.value = false;
}
</script>
