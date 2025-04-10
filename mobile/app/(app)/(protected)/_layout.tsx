import { Tabs } from "expo-router";
import React from "react";
import { Ionicons } from "@expo/vector-icons"; // ✅ Icon pack

import { colors } from "@/constants/colors";
import { useColorScheme } from "@/lib/useColorScheme";

export default function ProtectedLayout() {
	const { colorScheme } = useColorScheme();

	const iconColor =
		colorScheme === "dark" ? colors.dark.foreground : colors.light.foreground;

	return (
		<Tabs
			screenOptions={{
				headerShown: false,
				tabBarStyle: {
					backgroundColor:
						colorScheme === "dark"
							? colors.dark.background
							: colors.light.background,
				},
				tabBarActiveTintColor: iconColor,
				tabBarShowLabel: true,
			}}
		>
			<Tabs.Screen
				name="(products)/index"
				options={{
					title: "Products",
					tabBarIcon: ({ color, size }) => (
						<Ionicons name="list" color={color} size={22} />
					),
				}}
			/>
			<Tabs.Screen
				name="(chat)/list"
				options={{
					headerShown: true,
					title: "Chat",
					tabBarIcon: ({ color, size }) => (
						<Ionicons name="chatbubble" color={color} size={22} />
					),
				}}
			/>
			<Tabs.Screen
				name="index"
				options={{
					title: "Home",
					tabBarIcon: ({ color, size }) => (
						<Ionicons name="home" color={color} size={22} />
					),
				}}
			/>
			<Tabs.Screen
				name="my-product/list"
				options={{
					headerShown: true,
					title: "My Products",
					tabBarIcon: ({ color, size }) => (
						<Ionicons name="cube" color={color} size={22} />
					),
				}}
			/>
			<Tabs.Screen
				name="account"
				options={{
					title: "Account",
					tabBarIcon: ({ color, size }) => (
						<Ionicons name="person" color={color} size={22} />
					),
				}}
			/>
		</Tabs>
	);
}
