/** @type {import('./$types').LayoutServerLoad} */
export async function load({ locals }) {
	return {
		isAuthenticated: locals.isAuthenticated ?? false
	};
}
