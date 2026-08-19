<script>
	export let book;
</script>

<article class="book-card">
	<a href="/books/{book.id}" class="book-card__link">
		<div class="book-card__spine">
			{#if book.cover_image}
				<img src={book.cover_image} alt={book.title} class="book-card__cover" />
			{:else}
				<div class="book-card__placeholder">
					<div class="book-card__decoration">
						<span class="book-card__line"></span>
						<span class="book-card__line"></span>
					</div>
				</div>
			{/if}
		</div>
		<div class="book-card__body">
			<h3 class="book-card__title">{book.title}</h3>
			<p class="book-card__year text--mono">{book.year}</p>
			{#if book.authors && book.authors.length > 0}
				<p class="book-card__authors text--muted">
					by {book.authors.map((a) => a.full_name).join(', ')}
				</p>
			{/if}
		</div>
		<div class="book-card__number text--mono">
			#{String(book.id).padStart(4, '0')}
		</div>
	</a>
</article>

<style>
	.book-card {
		background: #fff;
		border: 1px solid rgba(43, 33, 24, 0.1);
		border-radius: 2px;
		overflow: hidden;
		transition: all var(--transition-base);
		box-shadow: 0 1px 3px rgba(43, 33, 24, 0.08);

		&:hover {
			transform: translateY(-2px);
			box-shadow:
				0 4px 12px rgba(43, 33, 24, 0.12),
				0 0 0 2px var(--color-accent);
		}
	}

	.book-card__link {
		display: block;
		text-decoration: none;
		color: inherit;
		border-bottom: none;
	}

	.book-card__spine {
		position: relative;
		width: 100%;
		height: 320px;
		background: linear-gradient(135deg, #f5f0e8 0%, #e8dfd5 100%);

		@media (--mobile) {
			height: 240px;
		}
	}

	.book-card__cover {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.book-card__placeholder {
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		background: linear-gradient(135deg, #e8dfd5 0%, #d4c4b0 100%);
		position: relative;
	}

	.book-card__decoration {
		display: flex;
		flex-direction: column;
		gap: 1rem;
		opacity: 0.3;
	}

	.book-card__line {
		width: 120px;
		height: 2px;
		background: var(--color-shelf);
	}

	.book-card__body {
		padding: var(--space-md);
	}

	.book-card__title {
		margin: 0 0 var(--space-xs) 0;
		font-size: 1.25rem;
		font-weight: 600;
		line-height: 1.3;
		color: var(--color-ink);
	}

	.book-card__year {
		color: var(--color-accent);
		font-weight: 500;
		margin: 0 0 var(--space-xs) 0;
	}

	.book-card__authors {
		font-size: 0.9rem;
		margin: 0;
		line-height: 1.4;
	}

	.book-card__number {
		position: absolute;
		top: var(--space-sm);
		right: var(--space-sm);
		background: rgba(249, 246, 241, 0.95);
		padding: var(--space-xs) var(--space-sm);
		border: 1px solid var(--color-muted);
		border-radius: 2px;
		font-size: 0.75rem;
		color: var(--color-shelf);
		backdrop-filter: blur(4px);
	}
</style>
