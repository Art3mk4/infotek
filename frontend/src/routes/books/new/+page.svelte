<script>
	import { enhance } from '$app/forms';

	export let data;
	export let form;

	let isSubmitting = false;
	$: ({ authors, error } = data);
</script>

<svelte:head>
	<title>Add Book — Catalog</title>
	<meta name="description" content="Add a new book to the catalog" />
</svelte:head>

<div class="page">
	<header class="page__header">
		<h1 class="page__title">Add Book</h1>
		<p class="page__subtitle">Create a new catalog entry</p>
	</header>

	{#if error}
		<div class="message message--error">
			<p>⚠️ {error}</p>
		</div>
	{/if}

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
			<label for="title">Title</label>
			<input type="text" id="title" name="title" required disabled={isSubmitting} />
		</div>

		<div class="form-row">
			<div class="form-group">
				<label for="year">Year</label>
				<input type="number" id="year" name="year" required min="1000" max="9999" disabled={isSubmitting} />
			</div>

			<div class="form-group">
				<label for="isbn">ISBN</label>
				<input type="text" id="isbn" name="isbn" disabled={isSubmitting} />
			</div>
		</div>

		<div class="form-group">
			<label for="description">Description</label>
			<textarea id="description" name="description" rows="4" disabled={isSubmitting}></textarea>
		</div>

		<div class="form-group">
			<label for="cover_image">Cover Image URL</label>
			<input type="url" id="cover_image" name="cover_image" disabled={isSubmitting} />
		</div>

		<div class="form-group">
			<label for="author_ids">Authors</label>
			<select id="author_ids" name="author_ids" multiple disabled={isSubmitting}>
				{#each authors as author}
					<option value={author.id}>{author.full_name}</option>
				{/each}
			</select>
			<span class="form-hint">Hold Ctrl/Cmd to select multiple</span>
		</div>

		<div class="form-actions">
			<a href="/books" class="btn">Cancel</a>
			<button type="submit" class="btn btn--primary" disabled={isSubmitting}>
				{isSubmitting ? 'Saving...' : 'Save Book'}
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

	.form-row {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: var(--space-md);
	}

	.form-actions {
		display: flex;
		gap: var(--space-md);
		margin-top: var(--space-lg);
	}

	.form-hint {
		display: block;
		font-size: 0.875rem;
		color: var(--color-muted);
		margin-top: var(--space-xs);
	}

	select[multiple] {
		min-height: 120px;
	}

	option {
		padding: var(--space-xs);
	}

	@media (--mobile) {
		.form-row {
			grid-template-columns: 1fr;
		}
	}
</style>
