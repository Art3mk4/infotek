<script>
	import { enhance } from '$app/forms';

	export let data;
	export let form;

	let isSubmitting = false;
	let isDeleting = false;
	$: ({ author } = data);
</script>

<svelte:head>
	<title>Edit {author.full_name} — Catalog</title>
	<meta name="description" content="Edit author profile" />
</svelte:head>

<div class="page">
	<header class="page__header">
		<h1 class="page__title">Edit Author</h1>
		<p class="page__subtitle">{author.full_name}</p>
	</header>

	{#if form?.error}
		<div class="message message--error">
			<p>⚠️ {form.error}</p>
		</div>
	{/if}

	<form class="form" method="POST" action="?/update" use:enhance={() => {
		isSubmitting = true;
		return async ({ update }) => {
			await update();
			isSubmitting = false;
		};
	}}>
		<div class="form-group">
			<label for="full_name">Full Name</label>
			<input type="text" id="full_name" name="full_name" value={author.full_name} required disabled={isSubmitting} />
		</div>

		<div class="form-actions">
			<a href="/authors/{author.id}" class="btn">Cancel</a>
			<button type="submit" class="btn btn--primary" disabled={isSubmitting}>
				{isSubmitting ? 'Saving...' : 'Save Changes'}
			</button>
		</div>
	</form>

	<form class="delete-form" method="POST" action="?/delete" use:enhance={() => {
		isDeleting = true;
		return async ({ update }) => {
			await update();
			isDeleting = false;
		};
	}}>
		<button type="submit" class="btn btn--danger" disabled={isDeleting}>
			{isDeleting ? 'Deleting...' : 'Delete Author'}
		</button>
	</form>
</div>

<style>
	.form {
		max-width: 700px;
		background: #fff;
		padding: var(--space-lg);
		border: 1px solid rgba(43, 33, 24, 0.1);
		border-radius: 2px;
	}

	.form-actions {
		display: flex;
		gap: var(--space-md);
		margin-top: var(--space-lg);
	}

	.delete-form {
		max-width: 700px;
		margin-top: var(--space-lg);
		padding-top: var(--space-lg);
		border-top: 1px solid rgba(43, 33, 24, 0.1);
	}

	.btn--danger {
		background: #c84b31;
		border-color: #c84b31;
		color: #fff;
	}
</style>
