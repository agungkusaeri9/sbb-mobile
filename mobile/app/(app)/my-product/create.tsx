import { useState, useEffect } from "react";
import {
	View,
	TextInput,
	Alert,
	Image,
	ActivityIndicator,
	ScrollView,
	TouchableOpacity,
} from "react-native";
import * as ImagePicker from "expo-image-picker";
import { SafeAreaView } from "react-native-safe-area-context";
import { Text } from "@/components/ui/text";
import { H1 } from "@/components/ui/typography";
import { Button } from "@/components/ui/button";
import { supabase } from "@/config/supabase";
import { decode } from "base64-arraybuffer";

export default function CreateProduct() {
	const [name, setName] = useState("");
	const [description, setDescription] = useState("");
	const [price, setPrice] = useState("");
	const [image, setImage] = useState<any>(null);
	const [imageUrl, setImageUrl] = useState<string>("");
	const [loading, setLoading] = useState(false);
	const [userId, setUserId] = useState<string | null>(null);

	useEffect(() => {
		const getUser = async () => {
			const {
				data: { session },
			} = await supabase.auth.getSession();
			setUserId(session?.user.id ?? null);
		};
		getUser();
	}, []);

	const pickImage = async () => {
		const permission = await ImagePicker.requestMediaLibraryPermissionsAsync();
		if (permission.status !== "granted") {
			alert("Permission required to access media library!");
			return;
		}

		const result = await ImagePicker.launchImageLibraryAsync({
			base64: true,
			quality: 0.7,
		});

		if (!result.canceled && result.assets && result.assets.length > 0) {
			setImage(result.assets[0]);
		}
	};

	const uploadImageToSupabase = async (file: any) => {
		const fileExt = file.uri.split(".").pop();
		const fileName = `product-${Date.now()}.${fileExt}`;
		const filePath = `${fileName}`;

		const { error } = await supabase.storage
			.from("product")
			.upload(filePath, decode(file.base64), {
				contentType: file.mimeType || "image/jpeg",
			});

		if (error) throw error;

		// get public URL
		const { data } = supabase.storage.from("product").getPublicUrl(filePath);
		return data.publicUrl;
	};

	const handleCreateProduct = async () => {
		if (!name || !price || !description || !image) {
			Alert.alert("Error", "Please fill in all fields.");
			return;
		}

		if (!userId) {
			Alert.alert("Error", "User not logged in.");
			return;
		}

		setLoading(true);
		try {
			const uploadedImageUrl = await uploadImageToSupabase(image);

			const { error } = await supabase.from("products").insert({
				name,
				description,
				price: parseFloat(price),
				image: uploadedImageUrl,
				status,
				user_id: userId,
			});

			if (error) throw error;

			Alert.alert("Success", "Product created!");
			setName("");
			setDescription("");
			setPrice("");
			setImage(null);
		} catch (err: any) {
			console.error(err);
			Alert.alert("Error", err.message || "Something went wrong.");
		} finally {
			setLoading(false);
		}
	};

	return (
		<SafeAreaView className="flex-1 bg-white p-4">
			<ScrollView className="gap-4">
				<H1 className="text-xl">Create Product</H1>

				{/* Name */}
				<TextInput
					className="border border-gray-300 rounded-md px-4 py-2"
					placeholder="Product name"
					value={name}
					onChangeText={setName}
				/>

				{/* Description */}
				<TextInput
					className="border border-gray-300 rounded-md px-4 py-2"
					placeholder="Description"
					value={description}
					onChangeText={setDescription}
				/>

				{/* Price */}
				<TextInput
					className="border border-gray-300 rounded-md px-4 py-2"
					placeholder="Price"
					value={price}
					onChangeText={setPrice}
					keyboardType="numeric"
				/>

				{/* Image Upload */}
				<Button className="text-white" onPress={pickImage}>
					Pick Image
				</Button>
				{image && (
					<Image
						source={{ uri: image.uri }}
						style={{ width: "100%", height: 200, borderRadius: 8 }}
						className="mt-2"
					/>
				)}

				<Button
					onPress={handleCreateProduct}
					className="mt-6"
					disabled={loading}
				>
					{loading ? <ActivityIndicator /> : <Text>Create Product</Text>}
				</Button>
			</ScrollView>
		</SafeAreaView>
	);
}
