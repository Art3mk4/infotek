import { API_BASE_URL } from '$lib/api.js';

/** @type {import('./$types').RequestHandler} */
export async function GET() {
	const [booksData, authorsData] = await Promise.all([
		fetch(`${API_BASE_URL}/books`).then((r) => r.json()),
		fetch(`${API_BASE_URL}/authors`).then((r) => r.json())
	]);

	const books = booksData.items || [];
	const authors = authorsData.items || [];
	const now = new Date().toISOString().split('T')[0];
	const siteUrl = 'http://localhost:3000';

	const urls = [
		{ loc: '/', priority: 1.0 },
		{ loc: '/books', priority: 0.9 },
		{ loc: '/authors', priority: 0.9 },
		{ loc: '/report', priority: 0.7 },
		...books.map((book) => ({ loc: `/books/${book.id}`, priority: 0.8 })),
		...authors.map((author) => ({ loc: `/authors/${author.id}`, priority: 0.8 }))
	];

	const body = `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
${urls
	.map(
		(url) => `  <url>
    <loc>${siteUrl}${url.loc}</loc>
    <lastmod>${now}</lastmod>
    <priority>${url.priority.toFixed(1)}</priority>
  </url>`
	)
	.join('\n')}
</urlset>
`;

	return new Response(body, {
		headers: {
			'Content-Type': 'application/xml'
		}
	});
}
