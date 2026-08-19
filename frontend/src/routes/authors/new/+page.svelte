<script>
	import { enhance } from '$app/forms';
	import PageHeader from '$lib/components/PageHeader.svelte';
	import ErrorMessage from '$lib/components/ErrorMessage.svelte';
	import { withLoading } from '$lib/formEnhance.js';

	export let form;
	let isSubmitting = false;
</script>

<svelte:head>
	<title>Add Author — Catalog</title>
	<meta name="description" content="Add a new author to the catalog" />
</svelte:head>

<div class="page">
	<PageHeader title="Add Author" subtitle="Create a new author profile" />

	<ErrorMessage message={form?.error} />

	<form class="form" method="POST" use:enhance={withLoading((v) => (isSubmitting = v))}>
		<div class="form-group">
			<label for="full_name">Full Name</label>
			<input type="text" id="full_name" name="full_name" required disabled={isSubmitting} />
		</div>

		<div class="form-actions">
			<a href="/authors" class="btn">Cancel</a>
			<button type="submit" class="btn btn--primary" disabled={isSubmitting}>
				{isSubmitting ? 'Saving...' : 'Save Author'}
			</button>
		</div>
	</form>
</div>
