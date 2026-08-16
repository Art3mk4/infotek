// API base URLs used during SSR and on the browser.
export const API_BASE_URL = import.meta.env.API_BASE_URL || 'http://backend:8080/api';
export const PUBLIC_API_BASE_URL = import.meta.env.PUBLIC_API_BASE_URL || 'http://localhost:8080/api';

/**
 * Server-side fetch helper. Pass the JWT token from the httpOnly cookie
 * when calling protected endpoints.
 */
export async function fetchApi(path, options = {}, token = null) {
	const url = `${API_BASE_URL}${path}`;
	const headers = {
		'Content-Type': 'application/json',
		...options.headers
	};

	if (token) {
		headers.Authorization = `Bearer ${token}`;
	}

	const response = await fetch(url, {
		...options,
		headers
	});

	if (!response.ok) {
		const error = await response.json().catch(() => ({ error: 'Request failed' }));
		throw new Error(error.error || `HTTP ${response.status}`);
	}

	return response.json();
}
