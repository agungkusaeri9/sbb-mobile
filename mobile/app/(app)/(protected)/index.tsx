import { useEffect, useState } from "react";
import { View } from "react-native";
import { router } from "expo-router";
import AsyncStorage from "@react-native-async-storage/async-storage";

import { Button } from "@/components/ui/button";
import { Text } from "@/components/ui/text";
import { H1, Muted } from "@/components/ui/typography";

export default function Home() {
	const [token, setToken] = useState<string | null>(null);

	useEffect(() => {
		const fetchToken = async () => {
			const storedToken = await AsyncStorage.getItem("access_token");
			setToken(storedToken);
		};
		fetchToken();
	}, []);

	return (
		<View className="flex-1 items-center justify-center bg-background p-4 gap-y-4">
			<H1 className="text-center">Beranda</H1>
			<Muted className="text-center"></Muted>

			<Button
				className="w-full"
				variant="default"
				size="default"
				onPress={() => router.push("/(app)/modal")}
			>
				<Text>Open Modal</Text>
			</Button>
		</View>
	);
}
