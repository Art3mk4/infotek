import { fetchApi } from '$lib/api';
import { redirect } from '@sveltejs/kit';

export const actions = {
    default: async ({ request, cookies }) => {
        const formData = await request.formData();
        const username = formData.get('username');
        const password = formData.get('password');

        try {
            const result = await fetchApi('/auth/login', {
                method: 'POST',
                body: JSON.stringify({ username, password })
            });

            // Set auth token in cookie
            cookies.set('auth_token', result.token, {
                path: '/',
                httpOnly: true,
                secure: false,
                sameSite: 'lax',
                maxAge: 60 * 60 * 24 // 1 day
            });

            throw redirect(303, '/books');
        } catch (error) {
            if (error.status === 303) throw error;
            return { success: false, error: error.message || 'Login failed' };
        }
    }
};
