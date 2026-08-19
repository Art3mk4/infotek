/**
 * Wraps a SvelteKit form `use:enhance` submit handler with the loading-flag
 * toggle every form on this site needs: flip on before submit, flip off once
 * the result has been applied.
 *
 * @param {(loading: boolean) => void} setLoading
 */
export function withLoading(setLoading) {
	return () => {
		setLoading(true);
		/** @param {{ update: () => Promise<void> }} result */
		return async ({ update }) => {
			await update();
			setLoading(false);
		};
	};
}
