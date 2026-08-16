<script>
	import { enhance } from '$app/forms';

	export let data;
	export let form;

	let isSubmitting = false;
	let isDeleting = false;
	$: ({ book, authors } = data);

	function isSelected(authorId) {
		return book.authors?.some((a) => a.id === authorId) ?? false;
	}
</script>

<svelte:head>
	<title>Edit {book.title} — Catalog</title>
	<meta name="description" content="Edit book details" />
</svelte:head>

<div class="page">
	<header class="page__header">
		<h1 class="page__title">Edit Book</h1>
		<p class="page__subtitle">{book.title}</p>
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
			<label for="title">Title</label>
			<input type="text" id="title" name="title" value={book.title} required disabled={isSubmitting} />
		</div>

		<div class="form-row">
			<div class="form-group">
				<label for="year">Year</label>
				<input type="number" id="year" name="year" value={book.year} required min="1000" max="9999" disabled={isSubmitting} />
			</div>

			<div class="form-group">
				<label for="isbn">ISBN</label>
				<input type="text" id="isbn" name="isbn" value={book.isbn || ''} disabled={isSubmitting} />
			</div>
		</div>

		<div class="form-group">
			<label for="description">Description</label>
			<textarea id="description" name="description" rows="4" disabled={isSubmitting}>{book.description || ''}</textarea>
		</div>

		<div class="form-group">
			<label for="cover_image">Cover Image URL</label>
			<input type="url" id="cover_image" name="cover_image" value={book.cover_image || ''} disabled={isSubmitting} />
		</div>

		<div class="form-group">
			<label for="author_ids">Authors</label>
			<select id="author_ids" name="author_ids" multiple disabled={isSubmitting}>
				{#each authors as author}
					<option value={author.id} selected={isSelected(author.id)}>{author.full_name}</option>
				{/each}
			</select>
			<span class="form-hint">Hold Ctrl/Cmd to select multiple</span>
		</div>

		<div class="form-actions">
			<a href="/books/{book.id}" class="btn">Cancel</a>
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
			{isDeleting ? 'Deleting...' : 'Delete Book'}
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
