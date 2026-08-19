import { redirect } from '@sveltejs/kit';
import { isTokenValid } from '$lib/jwt.js';

/** @type {import('@sveltejs/kit').Handle} */
export async function handle({ event, resolve }) {
	const authToken = event.cookies.get('auth_token');
	event.locals.authToken = authToken ?? null;
	event.locals.isAuthenticated = authToken ? isTokenValid(authToken) : false;

	if (authToken && !event.locals.isAuthenticated) {
		event.cookies.delete('auth_token', { path: '/' });
		event.locals.authToken = null;
	}

	const protectedPaths = [
		/^\/books\/new$/,
		/^\/books\/\d+\/edit$/,
		/^\/authors\/new$/,
		/^\/authors\/\d+\/edit$/
	];
	const isProtected = protectedPaths.some((pattern) => pattern.test(event.url.pathname));

	if (isProtected && !event.locals.isAuthenticated) {
		throw redirect(303, '/login');
	}

	return resolve(event);
}
