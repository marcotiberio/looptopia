# FormEvent — "Cookie check failed" fix

## Symptom

Some users submitting the public event form (`People Planet Pint`, etc.) saw
the error **`Cookie check failed`** and could not register.

## Root cause

The message was **not** from the theme's own validation — it came from
WordPress **core**, in `rest_cookie_check_errors()`, which runs *before* the
custom submission handler.

- The page rendered a `wp_rest` nonce baked into the HTML at render time
  (`functions.php` → `restNonce`).
- The form sent it as a header: `X-WP-Nonce` (`index.twig`).
- A WordPress nonce is tied to a user session + a 12h tick and is valid for
  only ~24h. Because it lives in the page HTML, it goes stale for anyone:
  - served a **cached / CDN** copy of the page,
  - who **left the tab open / bookmarked / prerendered** and submitted >24h later,
  - in a mismatched logged-in vs. logged-out context.
- When core fails to verify that nonce it returns the literal string
  `Cookie check failed` (code `rest_cookie_invalid_nonce`, HTTP 403).

That is why it only affected "some" users — those whose embedded nonce was no
longer valid at submit time.

Note there were **two** static nonces with the same weakness: the `wp_rest`
core nonce (threw "Cookie check failed") and the theme's own `NONCE_ACTION`
check (threw "Deine Sitzung ist abgelaufen"). Both break under caching/expiry.

## Fix (chosen approach: drop nonces entirely)

This is a public, cacheable submission form, so nonces are fundamentally
incompatible with full-page caching. Both nonce checks were removed and abuse
is contained by the remaining server-side protections.

### Changes — part 1: remove nonces

- **`inc/restApi/eventSubmission.php`** — removed the server-side nonce check;
  renumbered step comments.
- **`Components/FormEvent/index.twig`** — removed the `X-WP-Nonce` header (the
  direct cause) and the `nonce` form field.
- **`Components/FormEvent/functions.php`** — stopped generating/injecting
  `nonce` and `restNonce`; updated the explaining comment.
- **`inc/eventFields.php`** — removed the now-dead `NONCE_ACTION` constant.

### Changes — part 2: tolerate stale cached pages (server guard)

Removing the nonce fixes every **fresh** page load, but it can't un-deploy old
HTML already sitting in visitors' browsers (open tabs, bfcache, browser cache).
Those old pages still attach a now-expired `X-WP-Nonce` header, which core
rejects with "Cookie check failed" *before* our handler runs.

- **`inc/restApi/eventSubmission.php`** — added a `rest_authentication_errors`
  filter (priority 5, ahead of core's priority-100 `rest_cookie_check_errors`)
  that, for this one route (`/looptopia/v1/event`), strips any incoming
  `X-WP-Nonce` / `_wpnonce`. Core then treats the caller as anonymous (exactly
  what this endpoint expects) instead of failing the cookie-nonce check. This
  makes even months-old cached pages submit successfully.

### Live verification (production, LiteSpeed-cached)

Site runs **LiteSpeed Cache** (`x-litespeed-cache: hit`, `server: LiteSpeed`).

- Live `/bewerbung/` HTML confirmed to ship the fixed inline JS — `fetch()` with
  no `headers` and no nonce anywhere on the page.
- `POST /wp-json/looptopia/v1/event` with **no** nonce → `HTTP 422` + German
  validation errors (reaches our handler ✅).
- Same POST with a **stale** `X-WP-Nonce` → `HTTP 403`
  `{"code":"rest_cookie_invalid_nonce","message":"Cookie check failed"}` —
  reproduced the exact reported error, proving remaining failures come only
  from clients still sending an old nonce (stale cached HTML). The part-2 server
  guard neutralises this.

### Immediate workaround for an affected user

Hard-reload the page (Cmd/Ctrl+Shift+R) or close and reopen the tab, then
resubmit — this loads the fixed code. (No longer necessary once the part-2
guard is deployed.)

### Remaining protection

- Honeypot field (`website_hp`).
- Per-IP rate limit (5 submissions / hour).
- Strict sanitisation + choice allow-listing.
- Posts are always created as `pending` for editor review.

Worst case for a forged request is one extra pending post an editor reviews —
the same outcome as a normal submission.

## Deploy note

The front-end Twig/JS changed, so rebuild assets (`npm run build`) before
deploying.
