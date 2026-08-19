import { fetchApiWithAuth } from '$lib/serverApi.js';
import { error } from '@sveltejs/kit';

export async function load(event) {
	try {
		const author = await fetchApiWithAuth(event, `/authors/${event.params.id}`);
		return { author };
	} catch {
		throw error(404, 'Author not found');
	}
}

export const actions = {
	subscribe: async (event) => {
		const formData = await event.request.formData();
		const phone = formData.get('phone');

		try {
			await fetchApiWithAuth(event, '/subscriptions', {
				method: 'POST',
				body: JSON.stringify({
					author_id: parseInt(event.params.id),
					phone
				})
			});

			return { success: true, message: 'Successfully subscribed!' };
		} catch (error) {
			return { success: false, error: error.message };
		}
	}
};
