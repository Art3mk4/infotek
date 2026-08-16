<script>
	import { enhance } from '$app/forms';

	export let data;
	export let form;

	$: ({ author } = data);

	let isSubmitting = false;
</script>

<svelte:head>
	<title>{author.full_name} — Catalog</title>
	<meta name="description" content={`Author profile for ${author.full_name}`} />
</svelte:head>

<article class="author-detail">
	<div class="author-header">
		<div class="author-avatar-large">
			<span>✍️</span>
		</div>
		<div class="author-info">
			<h1>{author.full_name}</h1>
		</div>
	</div>

	<div class="subscribe-section">
		<h2>Subscribe to Author Updates</h2>
		<p>Get notified when this author publishes new books.</p>

		<form method="POST" action="?/subscribe" use:enhance={() => {
			isSubmitting = true;
			return async ({ update }) => {
				await update();
				isSubmitting = false;
			};
		}}>
			<div class="form-group">
				<label for="phone">Phone Number</label>
				<input
					type="tel"
					id="phone"
					name="phone"
					placeholder="+1234567890"
					required
					disabled={isSubmitting}
				/>
			</div>

			<button type="submit" class="btn btn--primary" disabled={isSubmitting}>
				{isSubmitting ? 'Subscribing...' : 'Subscribe'}
			</button>

			{#if form?.success}
				<p class="success">✓ {form.message}</p>
			{/if}

			{#if form?.error}
				<p class="error">✗ {form.error}</p>
			{/if}
		</form>
	</div>

	<div class="actions">
		<a href="/authors" class="btn">← Back to Authors</a>
		<a href="/authors/{author.id}/edit" class="btn btn--primary">Edit Author</a>
	</div>
</article>

<style>
	.author-detail {
		max-width: 700px;
		margin: 0 auto;
	}

	.author-header {
		text-align: center;
		margin-bottom: 3rem;
	}

	.author-avatar-large {
		width: 150px;
		height: 150px;
		margin: 0 auto 1.5rem;
		background: rgba(200, 75, 49, 0.1);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 4rem;
	}

	.author-info h1 {
		margin: 0;
		font-size: 2.5rem;
	}

	.subscribe-section {
		background: #fff;
		padding: 2rem;
		border-radius: 2px;
		border: 1px solid rgba(43, 33, 24, 0.1);
		margin-bottom: 2rem;
	}

	.subscribe-section h2 {
		margin-top: 0;
		color: var(--color-accent);
	}

	.subscribe-section p {
		color: var(--color-muted);
		margin-bottom: 1.5rem;
	}

	.actions {
		display: flex;
		gap: 1rem;
		flex-wrap: wrap;
	}

	.success {
		color: #2d6a4f;
		font-weight: 500;
		margin-top: 1rem;
	}

	.error {
		color: #c84b31;
		font-weight: 500;
		margin-top: 1rem;
	}
</style>
