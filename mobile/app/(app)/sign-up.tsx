import { useForm } from "react-hook-form";
import { View, TextInput, ActivityIndicator, Alert } from "react-native";

import { SafeAreaView } from "@/components/safe-area-view";
import { H1 } from "@/components/ui/typography";
import { Button } from "@/components/ui/button";
import { Text } from "@/components/ui/text";
import { register } from "@/services/api";
import { handleError } from "@/utils/handleError";
import { router } from "expo-router";

type FormData = {
	name: string;
	email: string;
	password: string;
	confirmPassword: string;
};

export default function SignUp() {
	const form = useForm<FormData>({
		defaultValues: {
			name: "",
			email: "",
			password: "",
			confirmPassword: "",
		},
	});

	async function onSubmit(data: FormData) {
		if (data.password !== data.confirmPassword) {
			Alert.alert("Error", "Passwords do not match.");
			return;
		}

		try {
			const payload = {
				name: data.name,
				email: data.email,
				password: data.password,
				password_confirmation: data.confirmPassword,
			};

			const res = await register(payload);
			console.log("Register success:", res);
			form.reset();
			Alert.alert("Success", "Registration successful!");

			router.push("/sign-in");
		} catch (error: any) {
			console.log(error);
			handleError(error);
		}
	}

	return (
		<SafeAreaView className="flex-1 bg-background p-4" edges={["bottom"]}>
			<View className="flex-1 gap-4">
				<H1 className="text-2xl mb-4">Sign Up</H1>

				<Text>Name</Text>
				<TextInput
					className="border rounded p-2 mb-2"
					placeholder="Name"
					onChangeText={form.setValue.bind(null, "name")}
					value={form.watch("name")}
				/>

				<Text>Email</Text>
				<TextInput
					className="border rounded p-2 mb-2"
					placeholder="Email"
					keyboardType="email-address"
					autoCapitalize="none"
					onChangeText={form.setValue.bind(null, "email")}
					value={form.watch("email")}
				/>

				<Text>Password</Text>
				<TextInput
					className="border rounded p-2 mb-2"
					placeholder="Password"
					secureTextEntry
					onChangeText={form.setValue.bind(null, "password")}
					value={form.watch("password")}
				/>

				<Text>Confirm Password</Text>
				<TextInput
					className="border rounded p-2 mb-4"
					placeholder="Confirm Password"
					secureTextEntry
					onChangeText={form.setValue.bind(null, "confirmPassword")}
					value={form.watch("confirmPassword")}
				/>

				<Button
					onPress={form.handleSubmit(onSubmit)}
					disabled={form.formState.isSubmitting}
				>
					{form.formState.isSubmitting ? (
						<ActivityIndicator size="small" />
					) : (
						<Text>Sign Up</Text>
					)}
				</Button>
			</View>
		</SafeAreaView>
	);
}
