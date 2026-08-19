<script>
	import { enhance } from '$app/forms';
	import PageHeader from '$lib/components/PageHeader.svelte';
	import ErrorMessage from '$lib/components/ErrorMessage.svelte';
	import DeleteForm from '$lib/components/DeleteForm.svelte';
	import { withLoading } from '$lib/formEnhance.js';

	export let data;
	export let form;

	let isSubmitting = false;
	$: ({ author } = data);
</script>

<svelte:head>
	<title>Edit {author.full_name} — Catalog</title>
	<meta name="description" content="Edit author profile" />
</svelte:head>

<div class="page">
	<PageHeader title="Edit Author" subtitle={author.full_name} />

	<ErrorMessage message={form?.error} />

	<form
		class="form"
		method="POST"
		action="?/update"
		use:enhance={withLoading((v) => (isSubmitting = v))}
	>
		<div class="form-group">
			<label for="full_name">Full Name</label>
			<input
				type="text"
				id="full_name"
				name="full_name"
				value={author.full_name}
				required
				disabled={isSubmitting}
			/>
		</div>

		<div class="form-actions">
			<a href="/authors/{author.id}" class="btn">Cancel</a>
			<button type="submit" class="btn btn--primary" disabled={isSubmitting}>
				{isSubmitting ? 'Saving...' : 'Save Changes'}
			</button>
		</div>
	</form>

	<DeleteForm label="Author" />
</div>
