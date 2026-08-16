import { fetchApiWithAuth } from '$lib/serverApi.js';

export async function load(event) {
	try {
		const data = await fetchApiWithAuth(event, '/authors');
		return {
			authors: data.items || [],
			pagination: {
				total: data.total || 0
			}
		};
	} catch (error) {
		console.error('Failed to load authors:', error);
		return {
			authors: [],
			pagination: { total: 0 },
			error: error.message
		};
	}
}
