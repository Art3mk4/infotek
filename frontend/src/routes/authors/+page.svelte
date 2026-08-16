<script>
	export let data;
	$: ({ authors, error } = data);
</script>

<svelte:head>
	<title>Authors - Books Catalog</title>
	<meta name="description" content="Browse authors in our catalog" />
</svelte:head>

<div class="page-header">
	<h1>Authors</h1>
	<p>Discover talented authors and their works</p>
</div>

{#if error}
	<div class="error-message">
		<p>⚠️ {error}</p>
	</div>
{/if}

{#if authors.length > 0}
	<div class="authors-grid">
		{#each authors as author}
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

<style>
	.page-header {
		margin-bottom: 3rem;
		text-align: center;
	}

	.page-header p {
		color: rgba(255, 255, 255, 0.6);
	}

	.error-message {
		background: rgba(255, 107, 107, 0.1);
		border: 1px solid rgba(255, 107, 107, 0.3);
		border-radius: 0.5rem;
		padding: 1rem;
		margin-bottom: 2rem;
		text-align: center;
	}

	.authors-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
		gap: 1.5rem;
	}

	.author-card {
		background: rgba(255, 255, 255, 0.05);
		padding: 2rem;
		border-radius: 0.5rem;
		text-align: center;
		text-decoration: none;
		color: inherit;
		transition: transform 0.2s ease, box-shadow 0.2s ease;
	}

	.author-card:hover {
		transform: translateY(-4px);
		box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
		text-decoration: none;
	}

	.author-avatar {
		width: 80px;
		height: 80px;
		margin: 0 auto 1rem;
		background: rgba(140, 209, 168, 0.2);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 2rem;
	}

	.author-card h3 {
		margin: 0;
		font-size: 1.1rem;
		color: #000000;
	}
</style>
