<script>
	import '../lib/styles/global.css';

	export let data;
	$: isAuthenticated = data.isAuthenticated;
</script>

<div class="app">
	<header class="header">
		<nav class="header__nav">
			<a href="/" class="logo">
				<span class="logo__mark">⌗</span>
				<span class="logo__text">Catalog</span>
			</a>
			<div class="nav">
				<a href="/books" class="nav__link">Browse</a>
				<a href="/authors" class="nav__link">Authors</a>
				<a href="/report" class="nav__link">Charts</a>
				{#if isAuthenticated}
					<a href="/books/new" class="nav__link">Add Book</a>
					<a href="/authors/new" class="nav__link">Add Author</a>
				{/if}
				<a href="/login" class="nav__link">{isAuthenticated ? 'Account' : 'Sign In'}</a>
			</div>
		</nav>
	</header>

	<main class="main">
		<slot />
	</main>

	<footer class="footer">
		<div class="footer__content">
			<p class="footer__note">A digital card catalog</p>
			<p class="footer__meta">Built with care, 2026</p>
		</div>
	</footer>
</div>

<style>
	.app {
		min-height: 100vh;
		display: flex;
		flex-direction: column;
	}

	.header {
		background: rgba(249, 246, 241, 0.95);
		border-bottom: 2px solid var(--color-shelf);
		padding: var(--space-md) 0;
		position: sticky;
		top: 0;
		z-index: 100;
		backdrop-filter: blur(8px);
	}

	.header__nav {
		max-width: 1200px;
		margin: 0 auto;
		padding: 0 var(--space-lg);
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.logo {
		display: flex;
		align-items: center;
		gap: var(--space-xs);
		text-decoration: none;
		border-bottom: none;
		color: var(--color-ink);
		font-family: var(--font-display);
		font-weight: 700;
		font-size: 1.5rem;
		letter-spacing: -0.01em;
	}

	.logo:hover {
		border-bottom: none;
	}

	.logo:hover .logo__mark {
		transform: rotate(90deg);
		transition: transform 0.3s ease;
	}

	.logo__mark {
		font-family: var(--font-mono);
		color: var(--color-accent);
		font-size: 1.75rem;
		line-height: 1;
	}

	.nav {
		display: flex;
		gap: var(--space-lg);
	}

	@media (--tablet) {
		.nav {
			gap: var(--space-md);
		}
	}

	.nav__link {
		color: var(--color-ink);
		text-decoration: none;
		font-weight: 500;
		font-size: 0.95rem;
		border-bottom: 2px solid transparent;
		padding-bottom: 2px;
		transition: border-color var(--transition-fast);
	}

	.nav__link:hover {
		border-bottom-color: var(--color-accent);
	}

	.main {
		flex: 1;
		width: 100%;
		max-width: 1200px;
		margin: 0 auto;
		padding: var(--space-xl) var(--space-lg);
	}

	.footer {
		border-top: 1px solid rgba(43, 33, 24, 0.15);
		padding: var(--space-lg) 0;
		margin-top: var(--space-xl);
	}

	.footer__content {
		max-width: 1200px;
		margin: 0 auto;
		padding: 0 var(--space-lg);
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	@media (--tablet) {
		.footer__content {
			flex-direction: column;
			gap: var(--space-sm);
			text-align: center;
		}
	}

	.footer__note {
		font-family: var(--font-display);
		font-style: italic;
		color: var(--color-muted);
		margin: 0;
	}

	.footer__meta {
		font-family: var(--font-mono);
		font-size: 0.75rem;
		color: var(--color-muted);
		letter-spacing: 0.05em;
		margin: 0;
	}
</style>
