export default defineNuxtPlugin(({ vueApp }) => {
  vueApp.directive("format-phone", {
    mounted(el, binding) {
      el.innerHTML = formatPhoneNumber(el.innerText);
    },
  });
});
