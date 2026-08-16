<script>
	import BookCard from '$lib/components/BookCard.svelte';
	import Button from '$lib/components/Button.svelte';

	export let data;
	$: ({ books, pagination, error } = data);

	function getPaginationLinks() {
		const links = [];
		const currentPage = pagination.page;
		const totalPages = pagination.totalPages;

		if (currentPage > 1) {
			links.push({ page: currentPage - 1, label: 'Previous' });
		}

		for (let i = 1; i <= totalPages; i++) {
			if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
				links.push({ page: i, label: i.toString(), current: i === currentPage });
			} else if (i === currentPage - 3 || i === currentPage + 3) {
				links.push({ page: i, label: '...', disabled: true });
			}
		}

		if (currentPage < totalPages) {
			links.push({ page: currentPage + 1, label: 'Next' });
		}

		return links;
	}
</script>

<svelte:head>
	<title>Browse Books — Catalog</title>
	<meta name="description" content="Browse our collection of books" />
</svelte:head>

<div class="page">
	<header class="page__header">
		<h1 class="page__title">Browse Books</h1>
		<p class="page__subtitle text--muted">
			{books.length} of {pagination.total} cataloged
		</p>
	</header>

	{#if error}
		<div class="message message--error">
			<p>⚠️ {error}</p>
		</div>
	{/if}

	{#if books.length > 0}
		<div class="grid">
			{#each books as book}
				<BookCard {book} />
			{/each}
		</div>

		{#if pagination.totalPages > 1}
			<nav class="pagination">
				{#each getPaginationLinks() as link}
					{#if link.disabled}
						<span class="pagination__item pagination__item--disabled">{link.label}</span>
					{:else if link.current}
						<span class="pagination__item pagination__item--current">{link.label}</span>
					{:else}
						<a href="/books?page={link.page}" class="pagination__item">{link.label}</a>
					{/if}
				{/each}
			</nav>
		{/if}
	{:else}
		<div class="empty-state">
			<p class="empty-state__text">No books found</p>
		</div>
	{/if}
</div>

<style>
	.page__header {
		margin-bottom: var(--space-xl);
		border-bottom: 2px solid rgba(43, 33, 24, 0.1);
		padding-bottom: var(--space-md);
	}

	.page__title {
		margin-bottom: var(--space-xs);
	}

	.page__subtitle {
		font-size: 1.1rem;
		margin: 0;
	}

	.pagination {
		display: flex;
		justify-content: center;
		gap: var(--space-xs);
		margin-top: var(--space-xl);
		flex-wrap: wrap;
	}

	.pagination__item {
		padding: var(--space-xs) var(--space-sm);
		background: #fff;
		border: 1px solid rgba(43, 33, 24, 0.2);
		border-radius: 2px;
		color: var(--color-ink);
		text-decoration: none;
		transition: all var(--transition-fast);
		font-family: var(--font-mono);
		font-size: 0.9rem;

		&:hover:not(.pagination__item--disabled):not(.pagination__item--current) {
			background: var(--color-accent);
			color: #fff;
			border-color: var(--color-accent);
		}

		&--current {
			background: var(--color-shelf);
			border-color: var(--color-shelf);
			color: #fff;
			font-weight: 600;
		}

		&--disabled {
			opacity: 0.4;
			cursor: default;
			border-bottom: none;
		}
	}

</style>
