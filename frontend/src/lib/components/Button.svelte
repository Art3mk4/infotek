<script>
	export let href = '/';
	export let variant = 'default'; // 'default' | 'primary'
	export let type = 'button';
</script>

{#if href && href !== '#'}
	<a {href} class="btn" class:btn--primary={variant === 'primary'}>
		<slot />
	</a>
{:else}
	<button {type} class="btn" class:btn--primary={variant === 'primary'} on:click>
		<slot />
	</button>
{/if}

<style>
	.btn {
		display: inline-block;
		padding: var(--space-sm) var(--space-lg);
		background: transparent;
		border: 2px solid var(--color-shelf);
		border-radius: 2px;
		color: var(--color-shelf);
		text-decoration: none;
		font-weight: 600;
		font-size: 0.95rem;
		cursor: pointer;
		transition: all var(--transition-base);
		font-family: var(--font-body);
		text-align: center;

		&:hover {
			background: var(--color-shelf);
			color: var(--color-paper);
			border-bottom: 2px solid var(--color-shelf);
			transform: translateY(-1px);
		}

		&:focus-visible {
			outline: 2px solid var(--color-accent);
			outline-offset: 2px;
		}

		&:active {
			transform: translateY(0);
		}

		&--primary {
			background: var(--color-accent);
			border-color: var(--color-accent);
			color: #fff;

			&:hover {
				background: var(--color-accent-light);
				border-color: var(--color-accent-light);
				color: #fff;
			}
		}
	}
</style>
