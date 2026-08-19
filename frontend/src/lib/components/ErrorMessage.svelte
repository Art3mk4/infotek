<script>
	export let message = '';
</script>

{#if message}
	<div class="message message--error">
		<p>⚠️ {message}</p>
	</div>
{/if}
