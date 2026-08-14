<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>{{ route('home') }}</loc>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>{{ route('jobs.index') }}</loc>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc>{{ route('contact.index') }}</loc>
    <changefreq>monthly</changefreq>
    <priority>0.4</priority>
  </url>
@foreach($jobs as $job)
  <url>
    <loc>{{ route('jobs.show', $job->id) }}</loc>
    <lastmod>{{ $job->updated_at?->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
@endforeach
</urlset>
