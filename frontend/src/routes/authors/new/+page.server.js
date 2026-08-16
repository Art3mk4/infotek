import { redirect } from '@sveltejs/kit';
import { fetchApiWithAuth } from '$lib/serverApi.js';

export const actions = {
	default: async (event) => {
		const formData = await event.request.formData();

		try {
			const author = await fetchApiWithAuth(event, '/authors', {
				method: 'POST',
				body: JSON.stringify({
					full_name: formData.get('full_name')
				})
			});

			throw redirect(303, `/authors/${author.id}`);
		} catch (error) {
			if (error.status === 303) throw error;
			return { success: false, error: error.message };
		}
	}
};
