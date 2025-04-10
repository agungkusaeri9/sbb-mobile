import { View, FlatList, Alert, TextInput } from "react-native";
import { useState } from "react";

import { Button } from "@/components/ui/button";
import { Text } from "@/components/ui/text";
import { Link, useRouter } from "expo-router";

export default function ListProduct() {
	const router = useRouter();

	const [products, setProducts] = useState([
		{ id: 1, name: "Produk A", price: 10000 },
		{ id: 2, name: "Produk B", price: 20000 },
	]);

	const [newName, setNewName] = useState("");
	const [newPrice, setNewPrice] = useState("");

	const addProduct = () => {
		if (!newName || !newPrice) return;

		const newProduct = {
			id: Date.now(),
			name: newName,
			price: parseInt(newPrice),
		};
		setProducts([...products, newProduct]);
		setNewName("");
		setNewPrice("");
	};

	const deleteProduct = (id: number) => {
		Alert.alert("Hapus Produk", "Yakin ingin menghapus?", [
			{ text: "Batal", style: "cancel" },
			{
				text: "Hapus",
				style: "destructive",
				onPress: () =>
					setProducts(products.filter((product) => product.id !== id)),
			},
		]);
	};

	return (
		<View className="flex-1 bg-background p-4 gap-y-4">
			<View>
				<Link
					className="bg-blue-800 text-center text-white px-4 py-2 rounded"
					href={"/my-product/create"}
				>
					Create Product
				</Link>
			</View>
			<FlatList
				data={products}
				keyExtractor={(item) => item.id.toString()}
				renderItem={({ item }) => (
					<View className="flex-row justify-between items-center p-3 bg-white dark:bg-gray-800 mb-2 rounded shadow">
						<View>
							<Text className="text-base font-semibold">{item.name}</Text>
							<Text className="text-sm text-muted-foreground">
								Rp {item.price}
							</Text>
						</View>
						<View className="flex-row gap-x-2">
							<Button
								variant="secondary"
								size="sm"
								onPress={() => alert("Edit belum dibuat")}
							>
								<Text>Edit</Text>
							</Button>
							<Button
								variant="destructive"
								size="sm"
								onPress={() => deleteProduct(item.id)}
							>
								<Text>Hapus</Text>
							</Button>
						</View>
					</View>
				)}
			/>
		</View>
	);
}
