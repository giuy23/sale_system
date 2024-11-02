import { computed, ref } from "vue";

export function useTypeInput() {
  const inputValue = ref([true, true, true]);

  const typeInput = computed(() => (value: boolean) => {
    let type = "password";
    let icon = "bx bx-hide";
    if (!value) {
      type = "text";
      icon = "bx bx-show";
    }
    return { type, icon };
  });

  return {
    inputValue,
    typeInput,
  };
}
