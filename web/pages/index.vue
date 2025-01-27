<template>
  <div class="h-screen">
    <div class="w-[700px] ml-auto mr-auto py-16">
      <div class="mb-8">
        <img
          class="mx-auto rounded-full h-24 w-24 mb-4"
          src="~/assets/images/logo.webp"
          alt="Spartan Burger Logo"
        />
        <Headline text="Spartan Burger" align="center" />
        <h2 class="text-center font-[500] text-balance text-base mt-[2px]">
          The best burgers in the galaxy
        </h2>
      </div>
      <div class="flex flex-col gap-4">
        <BioLink
          link="/reservation/check-register"
          icon="uil:calendar-alt"
          text="Book a Table"
        />
        <BioLink link="#" icon="uil:globe" text="Website" />
        <BioLink link="#" icon="uil:utensils-alt" text="Menu" />
        <BioLink
          link="https://www.instagram.com"
          icon="uil:instagram"
          text="Instagram"
        />
        <BioLink
          link="https://www.youtube.com"
          icon="uil:youtube"
          text="YouTube"
        />
        <BioLink
          link="https://www.whatsapp.com"
          icon="uil:whatsapp"
          text="WhatsApp"
        />
      </div>
      <div class="mt-8">
        <p class="text-center font-[500] text-secondary">
          © 2025 Spartan Burger. All rights reserved.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts"></script>
