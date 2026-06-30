<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('arlista') }}</loc>
        <lastmod>2026-06-01</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('galeria') }}</loc>
        <lastmod>2026-06-01</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @foreach($kategoriak as $kat)
    <url>
        <loc>{{ route('szolgaltatas.show', $kat->slug) }}</loc>
        <lastmod>{{ $kat->updated_at->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
    <url>
        <loc>{{ route('blog.index') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach($cikkek as $cikk)
    <url>
        <loc>{{ route('blog.show', $cikk->slug) }}</loc>
        <lastmod>{{ $cikk->updated_at->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
</urlset>
