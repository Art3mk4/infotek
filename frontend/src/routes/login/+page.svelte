<script>
	import { enhance } from '$app/forms';
	import { withLoading } from '$lib/formEnhance.js';

	export let form;

	let isSubmitting = false;
</script>

<svelte:head>
	<title>Login — Catalog</title>
	<meta name="description" content="Login to manage books and authors" />
</svelte:head>

<div class="login-page">
	<div class="login-card">
		<h1>Login</h1>
		<p class="subtitle">Access the catalog admin panel</p>

		<form method="POST" use:enhance={withLoading((v) => (isSubmitting = v))}>
			<div class="form-group">
				<label for="username">Username</label>
				<input
					type="text"
					id="username"
					name="username"
					required
					disabled={isSubmitting}
					placeholder="Enter your username"
				/>
			</div>

			<div class="form-group">
				<label for="password">Password</label>
				<input
					type="password"
					id="password"
					name="password"
					required
					disabled={isSubmitting}
					placeholder="Enter your password"
				/>
			</div>

			<button type="submit" class="btn btn--primary" disabled={isSubmitting}>
				{isSubmitting ? 'Logging in...' : 'Login'}
			</button>

			{#if form?.error}
				<p class="error">✗ {form.error}</p>
			{/if}
		</form>

		<div class="demo-credentials">
			<p><strong>Demo credentials:</strong></p>
			<p>Username: <code>admin</code></p>
			<p>Password: <code>admin123</code></p>
		</div>
	</div>
</div>

<style>
	.login-page {
		display: flex;
		align-items: center;
		justify-content: center;
		min-height: 60vh;
	}

	.login-card {
		max-width: 450px;
		width: 100%;
		background: #fff;
		padding: 3rem;
		border-radius: 2px;
		border: 1px solid rgba(43, 33, 24, 0.1);
		box-shadow: 0 4px 12px rgba(43, 33, 24, 0.05);
	}

	.login-card h1 {
		text-align: center;
		margin-bottom: 0.5rem;
	}

	.subtitle {
		text-align: center;
		color: var(--color-muted);
		margin-bottom: 2rem;
	}

	.error {
		color: #c84b31;
		font-weight: 500;
		margin-top: 1rem;
		text-align: center;
	}

	.demo-credentials {
		margin-top: 2rem;
		padding-top: 2rem;
		border-top: 1px solid rgba(43, 33, 24, 0.1);
		text-align: center;
		font-size: 0.9rem;
		color: var(--color-muted);
	}

	.demo-credentials code {
		background: rgba(43, 33, 24, 0.05);
		padding: 0.25rem 0.5rem;
		border-radius: 0.25rem;
		color: var(--color-accent);
		font-family: monospace;
	}

	.demo-credentials p {
		margin: 0.25rem 0;
	}
</style>
