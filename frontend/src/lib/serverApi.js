import { fetchApi } from './api.js';

/**
 * Convenience wrapper that reads the auth token from the httpOnly cookie
 * and forwards it to the backend API.
 */
export async function fetchApiWithAuth(event, path, options = {}) {
	const token = event.cookies.get('auth_token');
	return fetchApi(path, options, token);
}
