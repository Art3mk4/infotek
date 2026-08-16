<script>
	export let data;
	$: ({ book, isAuthenticated } = data);
</script>

<svelte:head>
	<title>{book.title} — Catalog</title>
	<meta name="description" content={book.description || `${book.title} by ${book.authors?.map(a => a.full_name).join(', ')}`} />
	<meta property="og:title" content={book.title} />
	<meta property="og:description" content={book.description || ''} />
	<meta property="og:type" content="book" />
</svelte:head>

{@html `<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "Book",
	"name": "${book.title}",
	"datePublished": "${book.year}",
	"isbn": "${book.isbn || ''}",
	"author": ${JSON.stringify(book.authors?.map(a => ({ "@type": "Person", "name": a.full_name })) || [])}
}
</script>`}

<article class="book-detail">
	<div class="book-detail__header">
		<div class="book-detail__cover-wrap">
			{#if book.cover_image}
				<img src={book.cover_image} alt={book.title} class="book-detail__cover" />
			{:else}
				<div class="book-detail__cover-placeholder">
					<div class="book-detail__spine-decoration">
						<span class="book-detail__spine-line"></span>
						<span class="book-detail__spine-line"></span>
						<span class="book-detail__spine-line"></span>
					</div>
				</div>
			{/if}
		</div>

		<div class="book-detail__info">
			<p class="book-detail__catalog text--mono">#{String(book.id).padStart(4, '0')}</p>
			<h1 class="book-detail__title">{book.title}</h1>
			<p class="book-detail__year">Published {book.year}</p>

			{#if book.isbn}
				<p class="book-detail__isbn text--mono">ISBN {book.isbn}</p>
			{/if}

			{#if book.authors && book.authors.length > 0}
				<div class="book-detail__authors">
					<h3 class="book-detail__authors-title">
						{book.authors.length === 1 ? 'Author' : 'Authors'}
					</h3>
					<ul class="book-detail__authors-list">
						{#each book.authors as author}
							<li class="book-detail__author-item">
								<a href="/authors/{author.id}" class="book-detail__author-link">
									{author.full_name}
								</a>
							</li>
						{/each}
					</ul>
				</div>
			{/if}
		</div>
	</div>

	{#if book.description}
		<div class="book-detail__description">
			<h2 class="book-detail__description-title">About this book</h2>
			<p class="book-detail__description-text">{book.description}</p>
		</div>
	{/if}

	<div class="book-detail__actions">
		<a href="/books" class="btn">← Back to catalog</a>
		{#if isAuthenticated}
			<a href="/books/{book.id}/edit" class="btn btn--primary">Edit Book</a>
		{/if}
	</div>
</article>

<style>
	.book-detail__header {
		display: grid;
		grid-template-columns: 300px 1fr;
		gap: var(--space-xl);
		margin-bottom: var(--space-xl);
		padding-bottom: var(--space-xl);
		border-bottom: 1px solid rgba(43, 33, 24, 0.1);

		@media (--tablet) {
			grid-template-columns: 1fr;
			gap: var(--space-lg);
		}
	}

	.book-detail__cover,
	.book-detail__cover-placeholder {
		width: 100%;
		height: 420px;
		object-fit: cover;
		border-radius: 2px;
		background: linear-gradient(135deg, #e8dfd5 0%, #d4c4b0 100%);
		box-shadow: 0 4px 12px rgba(43, 33, 24, 0.15);

		@media (--tablet) {
			height: 320px;
		}
	}

	.book-detail__cover-placeholder {
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.book-detail__spine-decoration {
		display: flex;
		flex-direction: column;
		gap: var(--space-md);
		opacity: 0.3;
	}

	.book-detail__spine-line {
		width: 140px;
		height: 2px;
		background: var(--color-shelf);
	}

	.book-detail__catalog {
		color: var(--color-accent);
		font-weight: 500;
		margin-bottom: var(--space-xs);
	}

	.book-detail__title {
		margin-bottom: var(--space-sm);
	}

	.book-detail__year {
		font-size: 1.2rem;
		color: var(--color-muted);
		font-weight: 500;
		margin-bottom: var(--space-sm);
	}

	.book-detail__isbn {
		color: var(--color-muted);
		margin-bottom: var(--space-lg);
	}

	.book-detail__authors-title {
		font-size: 1.1rem;
		margin-bottom: var(--space-sm);
		color: var(--color-shelf);
		text-transform: uppercase;
		letter-spacing: 0.05em;
		font-family: var(--font-body);
		font-weight: 600;
	}

	.book-detail__authors-list {
		list-style: none;
		padding: 0;
		margin: 0;
	}

	.book-detail__author-item {
		margin-bottom: var(--space-xs);
	}

	.book-detail__author-link {
		font-size: 1.15rem;
		color: var(--color-accent);

		&:hover {
			border-bottom-color: var(--color-accent);
		}
	}

	.book-detail__description {
		background: #fff;
		padding: var(--space-lg);
		border: 1px solid rgba(43, 33, 24, 0.1);
		border-radius: 2px;
		margin-bottom: var(--space-lg);
	}

	.book-detail__description-title {
		margin-top: 0;
		margin-bottom: var(--space-md);
		font-size: 1.75rem;
	}

	.book-detail__description-text {
		line-height: 1.8;
		font-size: 1.05rem;
		max-width: 75ch;
	}
</style>
