export default defineNuxtPlugin(({ vueApp }) => {
  vueApp.directive("format-cpf", {
    mounted(el, binding) {
      el.innerHTML = formatCPF(el.innerText);
    },
  });
});
