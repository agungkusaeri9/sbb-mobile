import { AxiosError } from "axios";
import { Alert } from "react-native";

/**
 * Handles different types of errors and displays appropriate toast messages.
 * @param error - The error object to handle
 */
export function handleError(error: unknown): void {
	if (error instanceof AxiosError) {
		// Handle HTTP 422 (Unprocessable Entity)
		if (error.response?.status === 422) {
			const errors = error.response.data.errors as Record<string, string[]>;
			const firstError = Object.values(errors)[0]?.[0];
			if (firstError) {
				Alert.alert(firstError);
			}
		}

		// Handle HTTP 401 (Unauthorized)
		if (error.response?.status === 401) {
			Alert.alert(error.response.data.meta.message);
		}

		// Handle other HTTP errors
		if (error.response?.data?.message) {
			Alert.alert(error.response.data.message);
		}

		// Default Axios error message
		// Alert.alert("An unexpected error occurred. Please try again.");
	} else if (error instanceof Error) {
		// Handle non-Axios errors (e.g., network issues, runtime errors)
		Alert.alert(error.message);
	} else {
		// Catch-all for unknown error types
		Alert.alert("An unknown error occurred.");
	}
}
