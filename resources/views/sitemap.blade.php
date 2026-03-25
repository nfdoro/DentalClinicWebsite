<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('arlista') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('galeria') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @foreach($kategoriak as $kat)
    <url>
        <loc>{{ route('szolgaltatas.show', $kat->slug) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
</urlset>
