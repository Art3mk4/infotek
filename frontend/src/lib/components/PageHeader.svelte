<script>
	export let title;
	export let subtitle = '';
	export let muted = false;
</script>

<header class="page__header">
	<h1 class="page__title">{title}</h1>
	{#if subtitle}
		<p class="page__subtitle" class:text--muted={muted}>{subtitle}</p>
	{/if}
</header>

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
</style>
