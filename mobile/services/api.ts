import axios from "axios";

const API_BASE_URL = "http://192.168.100.120:9000/api/v1";

const api = axios.create({
	baseURL: API_BASE_URL,
	headers: {
		Accept: "application/json",
	},
});

// Function untuk register
export const register = async (payload: {
	name: string;
	email: string;
	password: string;
	password_confirmation: string;
}) => {
	try {
		const response = await api.post("/auth/register", payload);
		return response.data;
	} catch (error: any) {
		throw error;
	}
};

// Function untuk login
export const login = async (payload: { email: string; password: string }) => {
	try {
		const response = await api.post("/auth/login", payload);
		return response.data;
	} catch (error: any) {
		throw error;
	}
};

// Optional: Set token jika login berhasil
export const setAuthToken = (token: string) => {
	api.defaults.headers.common["Authorization"] = `Bearer ${token}`;
};

// Optional: Hapus token
export const clearAuthToken = () => {
	delete api.defaults.headers.common["Authorization"];
};

export default {
	register,
	login,
	setAuthToken,
	clearAuthToken,
};
