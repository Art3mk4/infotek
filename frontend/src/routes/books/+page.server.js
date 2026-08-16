import { fetchApiWithAuth } from '$lib/serverApi.js';

export async function load(event) {
	const page = parseInt(event.url.searchParams.get('page') || '1');
	const perPage = 20;

	try {
		const data = await fetchApiWithAuth(event, `/books?page=${page}&per_page=${perPage}`);
		return {
			books: data.items || [],
			pagination: {
				page: data.page,
				perPage: data.per_page,
				total: data.total,
				totalPages: data.total_pages
			}
		};
	} catch (error) {
		console.error('Failed to load books:', error);
		return {
			books: [],
			pagination: { page: 1, perPage: 20, total: 0, totalPages: 0 },
			error: error.message
		};
	}
}
