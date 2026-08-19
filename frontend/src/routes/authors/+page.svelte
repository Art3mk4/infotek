<script>
	import PageHeader from '$lib/components/PageHeader.svelte';
	import ErrorMessage from '$lib/components/ErrorMessage.svelte';

	export let data;
	$: ({ authors, error } = data);
</script>

<svelte:head>
	<title>Authors - Books Catalog</title>
	<meta name="description" content="Browse authors in our catalog" />
</svelte:head>

<div class="page">
	<PageHeader title="Authors" subtitle="Discover talented authors and their works" />

	<ErrorMessage message={error} />

	{#if authors.length > 0}
		<div class="authors-grid">
			{#each authors as author (author.id)}
				<a href="/authors/{author.id}" class="author-card">
					<div class="author-avatar">
						<span>✍️</span>
					</div>
					<h3>{author.full_name}</h3>
				</a>
			{/each}
		</div>
	{:else}
		<div class="empty-state">
			<p class="empty-state__text">No authors found</p>
		</div>
	{/if}
</div>

<style>
	.authors-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
		gap: var(--space-lg);
	}

	.author-card {
		background: #fff;
		padding: var(--space-lg);
		border: 1px solid rgba(43, 33, 24, 0.1);
		border-radius: 2px;
		text-align: center;
		text-decoration: none;
		color: inherit;
		transition: all var(--transition-base);

		&:hover {
			transform: translateY(-2px);
			box-shadow:
				0 4px 12px rgba(43, 33, 24, 0.12),
				0 0 0 2px var(--color-accent);
			text-decoration: none;
		}
	}

	.author-avatar {
		width: 80px;
		height: 80px;
		margin: 0 auto var(--space-sm);
		background: rgba(200, 75, 49, 0.1);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 2rem;
	}

	.author-card h3 {
		margin: 0;
		font-size: 1.1rem;
		color: var(--color-ink);
	}
</style>
