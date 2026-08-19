<script>
	import { enhance } from '$app/forms';
	import PageHeader from '$lib/components/PageHeader.svelte';
	import ErrorMessage from '$lib/components/ErrorMessage.svelte';
	import DeleteForm from '$lib/components/DeleteForm.svelte';
	import { withLoading } from '$lib/formEnhance.js';

	export let data;
	export let form;

	let isSubmitting = false;
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
	<PageHeader title="Edit Book" subtitle={book.title} />

	<ErrorMessage message={form?.error} />

	<form
		class="form"
		method="POST"
		action="?/update"
		use:enhance={withLoading((v) => (isSubmitting = v))}
	>
		<div class="form-group">
			<label for="title">Title</label>
			<input
				type="text"
				id="title"
				name="title"
				value={book.title}
				required
				disabled={isSubmitting}
			/>
		</div>

		<div class="form-row">
			<div class="form-group">
				<label for="year">Year</label>
				<input
					type="number"
					id="year"
					name="year"
					value={book.year}
					required
					min="1000"
					max="9999"
					disabled={isSubmitting}
				/>
			</div>

			<div class="form-group">
				<label for="isbn">ISBN</label>
				<input type="text" id="isbn" name="isbn" value={book.isbn || ''} disabled={isSubmitting} />
			</div>
		</div>

		<div class="form-group">
			<label for="description">Description</label>
			<textarea id="description" name="description" rows="4" disabled={isSubmitting}
				>{book.description || ''}</textarea
			>
		</div>

		<div class="form-group">
			<label for="cover_image">Cover Image URL</label>
			<input
				type="url"
				id="cover_image"
				name="cover_image"
				value={book.cover_image || ''}
				disabled={isSubmitting}
			/>
		</div>

		<div class="form-group">
			<label for="author_ids">Authors</label>
			<select id="author_ids" name="author_ids" multiple disabled={isSubmitting}>
				{#each authors as author (author.id)}
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

	<DeleteForm label="Book" />
</div>
