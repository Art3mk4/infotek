import { redirect, error } from '@sveltejs/kit';
import { fetchApiWithAuth } from '$lib/serverApi.js';

export async function load(event) {
	try {
		const author = await fetchApiWithAuth(event, `/authors/${event.params.id}`);
		return { author };
	} catch {
		throw error(404, 'Author not found');
	}
}

export const actions = {
	update: async (event) => {
		const formData = await event.request.formData();

		try {
			await fetchApiWithAuth(event, `/authors/${event.params.id}`, {
				method: 'PUT',
				body: JSON.stringify({
					full_name: formData.get('full_name')
				})
			});

			throw redirect(303, `/authors/${event.params.id}`);
		} catch (error) {
			if (error.status === 303) throw error;
			return { success: false, error: error.message };
		}
	},

	delete: async (event) => {
		try {
			await fetchApiWithAuth(event, `/authors/${event.params.id}`, {
				method: 'DELETE'
			});
			throw redirect(303, '/authors');
		} catch (error) {
			if (error.status === 303) throw error;
			return { success: false, error: error.message };
		}
	}
};
