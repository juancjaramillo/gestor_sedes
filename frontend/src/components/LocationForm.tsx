import { useForm, type SubmitHandler } from "react-hook-form";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { Box, Button, Stack, TextField, Alert } from "@mui/material";
import api from "../lib/api";


const schema = z.object({
  code: z.string().min(1, "Code is required").max(10, "Max 10 chars"),
  name: z.string().min(1, "Name is required").max(100, "Max 100 chars"),
  image: z.string().url("Invalid URL").optional(), 
});

type FormData = z.infer<typeof schema>;

export default function LocationForm({ onCreated }: { onCreated: () => void }) {
  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
    reset,
    setError,
    clearErrors,
  } = useForm<FormData>({
    resolver: zodResolver(schema),
    defaultValues: { code: "", name: "", image: undefined }, // <- undefined, no cadena vacía
    mode: "onBlur",
  });

  const onSubmit: SubmitHandler<FormData> = async (data) => {
    clearErrors();
    try {
      await api.post("/v1/locations", data);
      reset({ code: "", name: "", image: undefined });
      onCreated();
    } catch (e: any) {
      setError("root", { type: "server", message: e?.message ?? "Request failed" });
    }
  };

  return (
    <Box component="form" onSubmit={handleSubmit(onSubmit)} sx={{ mb: 3 }}>
      {errors.root?.message && (
        <Alert severity="error" sx={{ mb: 2 }}>
          {errors.root.message}
        </Alert>
      )}
      <Stack direction={{ xs: "column", sm: "row" }} spacing={2}>
        <TextField
          label="Code"
          {...register("code")}
          error={!!errors.code}
          helperText={errors.code?.message}
        />
        <TextField
          label="Name"
          {...register("name")}
          error={!!errors.name}
          helperText={errors.name?.message}
        />
        <TextField
          label="Image URL"
         
          {...register("image", {
            setValueAs: (v) =>
              typeof v === "string" && v.trim() === "" ? undefined : v,
          })}
          error={!!errors.image}
          helperText={errors.image?.message}
        />
        <Button type="submit" variant="contained" disabled={isSubmitting}>
          Create
        </Button>
      </Stack>
    </Box>
  );
}
