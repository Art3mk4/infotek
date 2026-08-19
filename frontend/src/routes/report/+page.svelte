<script>
	import { goto } from '$app/navigation';
	import PageHeader from '$lib/components/PageHeader.svelte';
	import ErrorMessage from '$lib/components/ErrorMessage.svelte';

	export let data;
	$: ({ year, authors, error } = data);

	let selectedYear = year;

	function changeYear() {
		goto(`/report?year=${selectedYear}`);
	}

	function generateYearOptions() {
		const currentYear = new Date().getFullYear();
		const years = [];
		for (let y = currentYear; y >= currentYear - 10; y--) {
			years.push(y);
		}
		return years;
	}
</script>

<svelte:head>
	<title>Top Authors {year} - Books Catalog</title>
	<meta name="description" content={`Top 10 authors by books published in ${year}`} />
</svelte:head>

<div class="page">
	<PageHeader title="📊 Top Authors Report" subtitle="Most prolific authors by year" />

	<div class="year-selector">
		<label for="year">Select Year:</label>
		<select id="year" bind:value={selectedYear} on:change={changeYear}>
			{#each generateYearOptions() as y (y)}
				<option value={y}>{y}</option>
			{/each}
		</select>
	</div>

	<ErrorMessage message={error} />

	{#if authors.length > 0}
		<div class="report-content">
			<h2>Top 10 Authors in {year}</h2>
			<div class="leaderboard">
				{#each authors as author, index (author.id)}
					<div class="rank-item">
						<div class="rank-number">
							{#if index === 0}
								🥇
							{:else if index === 1}
								🥈
							{:else if index === 2}
								🥉
							{:else}
								<span class="number">{index + 1}</span>
							{/if}
						</div>
						<div class="rank-info">
							<h3>
								<a href="/authors/{author.id}">{author.full_name}</a>
							</h3>
							<p class="books-count">
								{author.books_count}
								{author.books_count === 1 ? 'book' : 'books'} published
							</p>
						</div>
						<div class="rank-badge">
							{author.books_count}
						</div>
					</div>
				{/each}
			</div>
		</div>
	{:else}
		<div class="empty-state">
			<p class="empty-state__text">📚 No books published in {year}</p>
		</div>
	{/if}
</div>

<style>
	.year-selector {
		max-width: 300px;
		margin: 0 auto 3rem;
		text-align: center;
	}

	.year-selector label {
		display: block;
		margin-bottom: 0.5rem;
		font-weight: 600;
		color: var(--color-shelf);
	}

	.year-selector select {
		width: 100%;
		padding: var(--space-sm) var(--space-md);
		background: #fff;
		border: 2px solid var(--color-muted);
		border-radius: 2px;
		color: var(--color-ink);
		font-size: 1rem;
		cursor: pointer;
	}

	.report-content {
		max-width: 800px;
		margin: 0 auto;
	}

	.report-content h2 {
		text-align: center;
		margin-bottom: 2rem;
		color: var(--color-shelf);
	}

	.leaderboard {
		display: flex;
		flex-direction: column;
		gap: 1rem;
	}

	.rank-item {
		display: flex;
		align-items: center;
		gap: 1.5rem;
		padding: 1.5rem;
		background: #fff;
		border: 1px solid rgba(43, 33, 24, 0.1);
		border-radius: 2px;
		transition:
			transform 0.2s ease,
			box-shadow 0.2s ease;
	}

	.rank-item:hover {
		transform: translateX(4px);
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	}

	.rank-number {
		font-size: 2rem;
		min-width: 50px;
		text-align: center;
	}

	.rank-number .number {
		font-weight: 700;
		color: var(--color-muted);
	}

	.rank-info {
		flex: 1;
	}

	.rank-info h3 {
		margin: 0 0 0.25rem 0;
		font-size: 1.25rem;
	}

	.rank-info h3 a {
		color: var(--color-ink);
		text-decoration: none;
		transition: color 0.2s ease;
	}

	.rank-info h3 a:hover {
		color: var(--color-accent);
	}

	.books-count {
		margin: 0;
		color: var(--color-muted);
		font-size: 0.9rem;
	}

	.rank-badge {
		font-size: 1.5rem;
		font-weight: 700;
		color: var(--color-accent);
		min-width: 60px;
		text-align: center;
	}
</style>
