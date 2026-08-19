import svelteConfig from './svelte.config.js';
import { defineConfig } from 'eslint/config';
import globals from 'globals';
import js from '@eslint/js';
import svelte from 'eslint-plugin-svelte';
import prettier from 'eslint-config-prettier';

export default defineConfig([
	{
		ignores: ['build/', '.svelte-kit/', 'node_modules/']
	},
	js.configs.recommended,
	svelte.configs.recommended,
	svelte.configs.prettier,
	prettier,
	{
		languageOptions: {
			globals: {
				...globals.browser,
				...globals.node
			}
		}
	},
	{
		files: ['**/*.svelte', '**/*.svelte.js'],
		languageOptions: {
			parserOptions: {
				svelteConfig
			}
		}
	},
	{
		rules: {
			// This app uses plain string hrefs (SvelteKit's classic routing), not the
			// newer typed-router resolve() helper.
			'svelte/no-navigation-without-resolve': 'off'
		}
	}
]);
