<script>
	import Button from '$lib/components/Button.svelte';
</script>

<svelte:head>
	<title>Catalog — Find Books Worth Reading</title>
	<meta
		name="description"
		content="A curated digital card catalog connecting readers with books and authors"
	/>
</svelte:head>

<div class="hero">
	<div class="hero-content">
		<p class="hero-eyebrow text-mono">Since 2026</p>
		<h1>Every book<br />has a story</h1>
		<p class="hero-body">
			Browse our card catalog. Subscribe to authors.<br />
			Track what matters in literature.
		</p>
		<div class="hero-actions">
			<Button href="/books" variant="primary">Start Browsing</Button>
			<Button href="/report">View Stats</Button>
		</div>
	</div>
</div>

<section class="features">
	<div class="container">
		<div class="section-header">
			<h2>How it works</h2>
			<p class="section-intro text-muted">
				Think of this as your personal librarian — organized, attentive, always ready.
			</p>
		</div>

		<div class="features-list">
			<div class="feature-item">
				<div class="feature-number text-mono">01</div>
				<div class="feature-content">
					<h3>Browse the collection</h3>
					<p>
						Search by title, author, or year. Each entry includes publication details and author
						information.
					</p>
				</div>
			</div>

			<div class="feature-item">
				<div class="feature-number text-mono">02</div>
				<div class="feature-content">
					<h3>Follow authors you care about</h3>
					<p>Subscribe to get notified when they publish. No spam, just what you asked for.</p>
				</div>
			</div>

			<div class="feature-item">
				<div class="feature-number text-mono">03</div>
				<div class="feature-content">
					<h3>See what's trending</h3>
					<p>Annual charts show the most prolific authors by publication count.</p>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="cta-section">
	<div class="cta-card">
		<h2>Ready to explore?</h2>
		<p>Over a thousand books cataloged and counting.</p>
		<Button href="/books" variant="primary">View the catalog</Button>
	</div>
</section>

<style>
	.hero {
		min-height: 75vh;
		display: flex;
		align-items: center;
		justify-content: center;
		text-align: center;
		padding: var(--space-xl) var(--space-lg);
		background:
			linear-gradient(135deg, rgba(200, 75, 49, 0.04) 0%, transparent 50%),
			radial-gradient(circle at 30% 40%, rgba(139, 115, 85, 0.08) 0%, transparent 60%);
	}

	.hero-eyebrow {
		color: var(--color-accent);
		font-weight: 500;
		margin-bottom: var(--space-sm);
		letter-spacing: 0.1em;
		text-transform: uppercase;
	}

	.hero-content h1 {
		font-size: clamp(2.5rem, 6vw, 5rem);
		margin-bottom: var(--space-md);
		line-height: 1.1;
		max-width: 12ch;
		margin-left: auto;
		margin-right: auto;
	}

	.hero-body {
		font-size: clamp(1.1rem, 2vw, 1.35rem);
		color: var(--color-muted);
		margin-bottom: var(--space-lg);
		line-height: 1.6;
		max-width: 50ch;
		margin-left: auto;
		margin-right: auto;
	}

	.hero-actions {
		display: flex;
		gap: var(--space-md);
		justify-content: center;
		flex-wrap: wrap;
	}

	.features {
		padding: var(--space-xl) 0;
		border-top: 1px solid rgba(43, 33, 24, 0.1);
	}

	.section-header {
		text-align: center;
		margin-bottom: var(--space-xl);
	}

	.section-header h2 {
		margin-bottom: var(--space-sm);
	}

	.section-intro {
		font-size: 1.15rem;
		max-width: 50ch;
		margin: 0 auto;
	}

	.features-list {
		max-width: 800px;
		margin: 0 auto;
		display: flex;
		flex-direction: column;
		gap: var(--space-lg);
	}

	.feature-item {
		display: grid;
		grid-template-columns: 80px 1fr;
		gap: var(--space-lg);
		padding: var(--space-lg);
		background: #fff;
		border: 1px solid rgba(43, 33, 24, 0.1);
		border-radius: 2px;
		transition: all 0.2s ease;
	}

	.feature-item:hover {
		border-color: var(--color-accent);
		box-shadow: 0 2px 8px rgba(43, 33, 24, 0.08);
	}

	.feature-number {
		font-size: 2rem;
		font-weight: 500;
		color: var(--color-accent);
		line-height: 1;
	}

	.feature-content h3 {
		margin-bottom: var(--space-xs);
		font-size: 1.35rem;
	}

	.feature-content p {
		margin: 0;
		color: var(--color-muted);
		line-height: 1.6;
	}

	.cta-section {
		padding: var(--space-xl) 0;
		text-align: center;
	}

	.cta-card {
		max-width: 600px;
		margin: 0 auto;
		padding: var(--space-xl);
		background: var(--color-accent);
		color: #fff;
		border-radius: 2px;
	}

	.cta-card h2 {
		color: #fff;
		margin-bottom: var(--space-sm);
	}

	.cta-card p {
		font-size: 1.15rem;
		margin-bottom: var(--space-lg);
		opacity: 0.95;
	}

	@media (max-width: 768px) {
		.feature-item {
			grid-template-columns: 1fr;
			gap: var(--space-sm);
		}

		.feature-number {
			font-size: 1.5rem;
		}
	}
</style>
