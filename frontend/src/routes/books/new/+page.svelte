<script>
	import { enhance } from '$app/forms';
	import PageHeader from '$lib/components/PageHeader.svelte';
	import ErrorMessage from '$lib/components/ErrorMessage.svelte';
	import { withLoading } from '$lib/formEnhance.js';

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
	<PageHeader title="Add Book" subtitle="Create a new catalog entry" />

	<ErrorMessage message={error} />
	<ErrorMessage message={form?.error} />

	<form class="form" method="POST" use:enhance={withLoading((v) => (isSubmitting = v))}>
		<div class="form-group">
			<label for="title">Title</label>
			<input type="text" id="title" name="title" required disabled={isSubmitting} />
		</div>

		<div class="form-row">
			<div class="form-group">
				<label for="year">Year</label>
				<input
					type="number"
					id="year"
					name="year"
					required
					min="1000"
					max="9999"
					disabled={isSubmitting}
				/>
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
				{#each authors as author (author.id)}
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
