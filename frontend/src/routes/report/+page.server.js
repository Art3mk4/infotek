import { fetchApiWithAuth } from '$lib/serverApi.js';

export async function load(event) {
	const year = parseInt(event.url.searchParams.get('year') || new Date().getFullYear().toString());

	try {
		const data = await fetchApiWithAuth(event, `/report/top-authors?year=${year}`);
		return {
			year: data.year,
			authors: data.authors || []
		};
	} catch (error) {
		console.error('Failed to load report:', error);
		return {
			year,
			authors: [],
			error: error.message
		};
	}
}
