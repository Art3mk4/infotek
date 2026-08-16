import { redirect } from '@sveltejs/kit';
import { fetchApiWithAuth } from '$lib/serverApi.js';

export async function load(event) {
	try {
		const data = await fetchApiWithAuth(event, '/authors');
		return { authors: data.items || [] };
	} catch (error) {
		console.error('Failed to load authors:', error);
		return { authors: [], error: error.message };
	}
}

export const actions = {
	default: async (event) => {
		const formData = await event.request.formData();
		const authorIds = formData.getAll('author_ids').map((id) => parseInt(id));

		try {
			const book = await fetchApiWithAuth(event, '/books', {
				method: 'POST',
				body: JSON.stringify({
					title: formData.get('title'),
					year: parseInt(formData.get('year')),
					description: formData.get('description') || null,
					isbn: formData.get('isbn') || null,
					cover_image: formData.get('cover_image') || null,
					author_ids: authorIds
				})
			});

			throw redirect(303, `/books/${book.id}`);
		} catch (error) {
			if (error.status === 303) throw error;
			return { success: false, error: error.message };
		}
	}
};
