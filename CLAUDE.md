# Digital Signage

Laravel monolith for running lobby screens: an admin panel manages content,
playlists and screens; each screen opens a public fullscreen page that polls
for its own playlist.

## Stack

- Laravel 13, PHP `^8.3`
- Blade + Alpine.js 3 + Tailwind 3, Breeze (Blade stack), SweetAlert2
- No SPA and no general API. The only JSON endpoint is the display poll.

Admin-facing copy is Indonesian. Code, identifiers and comments are English.

## Commands

```sh
php artisan test              # PHPUnit
vendor/bin/pint --dirty       # format before finishing
npm run build                 # compile assets
```

## Public display

`/display/{unique_code}` renders the screen; it polls
`/api/display/{unique_code}/contents` every 10 seconds and swaps content in
place, so a screen never needs a manual refresh.

Two things are easy to break there:

- `fetchContents()` skips re-rendering when the content signature is
  unchanged. Anything that must stay live regardless of content — screen
  name, location, orientation — has to be applied *before* that early
  return. That is what `applyDisplayMeta()` does.
- Orientation-dependent Tailwind classes are bound with `:class`, not
  rendered server-side, so a screen can be re-oriented without a reload.
  Keep both branches of each ternary as literal strings or Tailwind will
  not generate the class.

## Content background colours

`Content::BACKGROUND_COLORS` is a fixed palette, not a free colour picker:
every entry must clear WCAG AA (>= 4.5:1) against the white slide text so an
announcement stays readable across a lobby.
`ContentBackgroundColorTest` enforces this. If a colour fails, change the
colour — never the threshold.

## Production notes

Deployed to Hostinger shared hosting.

- The default `php` there is 8.2 and will not satisfy `^8.3`. Every PHP and
  Composer command must run through `/opt/alt/php83/usr/bin/php`.
- Only `public/` may be web-served. The project root must never be
  reachable; `.env` must return 403.
- The server has no Node. Run `npm run build` locally and upload
  `public/build`.
- Never run `db:seed` in production: the seeded admin credentials are
  visible in this public repository.

## Tooling

Laravel Boost was installed once and removed at the user's request. Do not
re-add it without asking.
