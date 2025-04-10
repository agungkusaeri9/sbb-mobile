import { View, FlatList, TouchableOpacity, Image } from "react-native";
import { Text } from "@/components/ui/text";

const chatList = [
	{
		id: "1",
		name: "Adit",
		lastMessage: "Halo, bro!",
		avatar: "https://i.pravatar.cc/100?img=1",
	},
	{
		id: "2",
		name: "Nita",
		lastMessage: "Terima kasih ya!",
		avatar: "https://i.pravatar.cc/100?img=2",
	},
	{
		id: "3",
		name: "Admin Toko",
		lastMessage: "Barangnya sudah dikirim.",
		avatar: "https://i.pravatar.cc/100?img=3",
	},
];

export default function ChatScreen() {
	return (
		<View className="flex-1 bg-background p-4">
			<FlatList
				data={chatList}
				keyExtractor={(item) => item.id}
				renderItem={({ item }) => (
					<TouchableOpacity className="flex-row items-center py-3 border-b border-border">
						<Image
							source={{ uri: item.avatar }}
							className="w-12 h-12 rounded-full mr-4"
						/>
						<View className="flex-1">
							<Text className="font-semibold text-lg text-foreground">
								{item.name}
							</Text>
							<Text className="text-muted-foreground text-sm" numberOfLines={1}>
								{item.lastMessage}
							</Text>
						</View>
					</TouchableOpacity>
				)}
			/>
		</View>
	);
}
