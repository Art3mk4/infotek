<script>
	import { enhance } from '$app/forms';

	export let form;
	let isSubmitting = false;
</script>

<svelte:head>
	<title>Add Author — Catalog</title>
	<meta name="description" content="Add a new author to the catalog" />
</svelte:head>

<div class="page">
	<header class="page__header">
		<h1 class="page__title">Add Author</h1>
		<p class="page__subtitle">Create a new author profile</p>
	</header>

	{#if form?.error}
		<div class="message message--error">
			<p>⚠️ {form.error}</p>
		</div>
	{/if}

	<form class="form" method="POST" use:enhance={() => {
		isSubmitting = true;
		return async ({ update }) => {
			await update();
			isSubmitting = false;
		};
	}}>
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
</style>
