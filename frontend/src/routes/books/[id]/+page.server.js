import { fetchApiWithAuth } from '$lib/serverApi.js';
import { error } from '@sveltejs/kit';

export async function load(event) {
	try {
		const book = await fetchApiWithAuth(event, `/books/${event.params.id}`);
		return { book, isAuthenticated: event.locals.isAuthenticated ?? false };
	} catch (err) {
		throw error(404, 'Book not found');
	}
}
