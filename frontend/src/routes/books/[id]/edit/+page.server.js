import { redirect, error } from '@sveltejs/kit';
import { fetchApiWithAuth } from '$lib/serverApi.js';

export async function load(event) {
	try {
		const [book, authors] = await Promise.all([
			fetchApiWithAuth(event, `/books/${event.params.id}`),
			fetchApiWithAuth(event, '/authors').then((data) => data.items || [])
		]);
		return { book, authors };
	} catch (err) {
		throw error(404, 'Book not found');
	}
}

export const actions = {
	update: async (event) => {
		const formData = await event.request.formData();
		const authorIds = formData.getAll('author_ids').map((id) => parseInt(id));

		try {
			await fetchApiWithAuth(event, `/books/${event.params.id}`, {
				method: 'PUT',
				body: JSON.stringify({
					title: formData.get('title'),
					year: parseInt(formData.get('year')),
					description: formData.get('description') || null,
					isbn: formData.get('isbn') || null,
					cover_image: formData.get('cover_image') || null,
					author_ids: authorIds
				})
			});

			throw redirect(303, `/books/${event.params.id}`);
		} catch (error) {
			if (error.status === 303) throw error;
			return { success: false, error: error.message };
		}
	},

	delete: async (event) => {
		try {
			await fetchApiWithAuth(event, `/books/${event.params.id}`, {
				method: 'DELETE'
			});
			throw redirect(303, '/books');
		} catch (error) {
			if (error.status === 303) throw error;
			return { success: false, error: error.message };
		}
	}
};
