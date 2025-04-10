import { useForm } from "react-hook-form";
import { ActivityIndicator, Alert, TextInput, View } from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";

import { SafeAreaView } from "@/components/safe-area-view";
import { Button } from "@/components/ui/button";
import { Text } from "@/components/ui/text";
import { H1 } from "@/components/ui/typography";
import { login } from "@/services/api"; // fungsi login Laravel API
import { handleError } from "@/utils/handleError";
import { router } from "expo-router";

type FormData = {
	email: string;
	password: string;
};

export default function SignIn() {
	const form = useForm<FormData>({
		defaultValues: {
			email: "",
			password: "",
		},
	});

	async function onSubmit(data: FormData) {
		try {
			const res = await login(data);
			const token = res?.data?.access_token;

			if (token) {
				await AsyncStorage.setItem("access_token", token);
				Alert.alert("Success", "Login successful!");
				form.reset();

				router.push("/(app)/(protected)");
			} else {
				Alert.alert("Login Failed", "Token not found.");
			}
		} catch (error) {
			console.log(error);
			handleError(error); // tangani error dari backend
		}
	}

	return (
		<SafeAreaView className="flex-1 bg-background p-4" edges={["bottom"]}>
			<View className="flex-1 gap-4">
				<H1 className="text-2xl mb-4">Sign In</H1>

				<Text>Email</Text>
				<TextInput
					className="border rounded p-2 mb-2"
					placeholder="Email"
					autoCapitalize="none"
					keyboardType="email-address"
					onChangeText={form.setValue.bind(null, "email")}
					value={form.watch("email")}
				/>

				<Text>Password</Text>
				<TextInput
					className="border rounded p-2 mb-4"
					placeholder="Password"
					secureTextEntry
					onChangeText={form.setValue.bind(null, "password")}
					value={form.watch("password")}
				/>

				<Button
					onPress={form.handleSubmit(onSubmit)}
					disabled={form.formState.isSubmitting}
				>
					{form.formState.isSubmitting ? (
						<ActivityIndicator size="small" />
					) : (
						<Text>Sign In</Text>
					)}
				</Button>
			</View>
		</SafeAreaView>
	);
}
