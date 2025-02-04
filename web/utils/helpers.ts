export const formatCPF = (value: string) => {
  return value
    .replace(/\D/g, "") // Remove non-numeric characters
    .slice(0, 11) // Limit to 11 characters
    .replace(/(\d{3})(\d)/, "$1.$2") // Add first dot
    .replace(/(\d{3})(\d)/, "$1.$2") // Add second dot
    .replace(/(\d{3})(\d{1,2})$/, "$1-$2"); // Add hyphen
};

export const extractError = (error: any) => {
  const errors = error.data.errors;
  const key = Object.keys(errors)[0];

  const message = {
    text: typeof errors[key] === "string" ? errors[key] : errors[key][0],
    key,
  };

  return message;
};
