<script>
	import { enhance } from '$app/forms';
	import { withLoading } from '$lib/formEnhance.js';

	export let label;

	let isDeleting = false;
</script>

<form
	class="delete-form"
	method="POST"
	action="?/delete"
	use:enhance={withLoading((v) => (isDeleting = v))}
>
	<button type="submit" class="btn btn--danger" disabled={isDeleting}>
		{isDeleting ? 'Deleting...' : `Delete ${label}`}
	</button>
</form>

<style>
	.delete-form {
		max-width: 700px;
		margin-top: var(--space-lg);
		padding-top: var(--space-lg);
		border-top: 1px solid rgba(43, 33, 24, 0.1);
	}
</style>
