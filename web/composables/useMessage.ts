export default function () {
  const toast = useToast();

  const timeout = 3000;

  const successMessage = (message: string) => {
    toast.add({
      id: "success",
      title: "Success!",
      description: message,
      icon: "i-heroicons-check-circle-20-solid",
      color: "green",
      timeout,
    });
  };

  const errorMessage = (message: string) => {
    toast.add({
      id: "error",
      title: "Error!",
      description: message,
      icon: "i-heroicons-x-circle-20-solid",
      color: "red",
      timeout,
    });
  };

  const warningMessage = (message: string) => {
    toast.add({
      id: "warning",
      title: "Warning!",
      description: message,
      icon: "i-heroicons-exclamation-triangle-20-solid",
      color: "yellow",
      timeout,
    });
  };

  const customMessage = (
    title: string,
    message: string,
    color: any,
    icon: string,
    duration: number = timeout
  ) => {
    toast.add({
      id: "custom",
      title,
      description: message,
      icon,
      color,
      timeout: duration,
    });
  };

  return { successMessage, errorMessage, warningMessage, customMessage };
}
