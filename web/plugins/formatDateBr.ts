export default defineNuxtPlugin(({ vueApp }) => {
  vueApp.directive("format-date-br", {
    mounted(el, binding) {
      el.innerHTML = formatDateToBR(el.innerText);
    },
  });
});
