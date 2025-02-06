export const formatCPF = (value: string) => {
  return value
    .replace(/\D/g, "") // Remove non-numeric characters
    .slice(0, 11) // Limit to 11 characters
    .replace(/(\d{3})(\d)/, "$1.$2") // Add first dot
    .replace(/(\d{3})(\d)/, "$1.$2") // Add second dot
    .replace(/(\d{3})(\d{1,2})$/, "$1-$2"); // Add hyphen
};

export const formatPhoneNumber = (value: string): string => {
  return value
    .replace(/\D/g, "") // Remove caracteres não numéricos
    .slice(0, 11) // Limita a 11 caracteres
    .replace(/(\d{2})(\d)/, "($1) $2") // Coloca parênteses após os dois primeiros dígitos
    .replace(/(\d{5})(\d)/, "$1-$2"); // Coloca hífen após os cinco primeiros dígitos
};

export const formatDateToBR = (data: string): string => {
  const dataObj = new Date(data);

  return dataObj.toLocaleString("pt-BR", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });
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
