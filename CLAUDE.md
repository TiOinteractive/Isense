# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A custom CMS for **resinet.pl** built on **CodeIgniter 4** (framework lives in `vendor/codeigniter4/framework`). The app serves a public multilingual website plus a slug-protected admin panel, and is extended through a home-grown **module system** under `modules/`. The web root is `public/` (point the web server there, not the project root).

> Framework is on **4.7.3** (see `composer.lock`), compatible with the local **PHP 8.4.15** (WAMP). The `app/Config` files were synced to the 4.7 reference during the upgrade; app-customized configs (App, Autoload, Database, Email, Images, Pager, Paths, Validation, Filters, Routes) were preserved/merged rather than overwritten.

## Commands

All commands run from the `html/` directory.

```bash
composer install                 # install dependencies
php spark serve                  # local dev server (http://localhost:8080)
php spark routes                 # list resolved routes
php spark cache:clear            # clear file cache (writable/cache)
php spark list                   # all available CLI commands

vendor/bin/phpunit               # run the full test suite (tests/)
vendor/bin/phpunit tests/unit/Foo.php          # run one test file
vendor/bin/phpunit --filter testMethodName     # run a single test
```

There is no JS/CSS build step — front-end assets are concatenated/served at runtime (see Assets below).

## Configuration

- Copy `env` → `.env`. Key custom settings beyond standard CI4:
  - `ADMIN_PANEL_SLUG` — the URL segment the entire admin panel lives under. Routes and controllers read it via `env('ADMIN_PANEL_SLUG')`; never hard-code an admin path.
  - Multiple database groups are configured: `default`, `newsletter`, `entertainment`, `oldresinet`, `autresinet`, `shopping`. Code that needs a non-default connection passes the group name explicitly (e.g. modules like Newsletter/Shopping use their own group); `defaultGroup` is `default`.
  - `images.*` keys configure upload limits and allowed MIME types for the file manager / image pipeline.
- Locales: `pl` (default) and `en`, `negotiateLocale = false`. The non-default locale appears as a leading URL segment (`/en/...`); routes are duplicated inside a "locale rules" block in `Routes.php`.

## Request flow & routing

`app/Config/Routes.php` defines explicit routes (auto-routing is off). The ordering matters:

1. Specific utility routes first: `Assets` (`/tio.css`, `/tio.js`), `RenderImage` (`/image/...`), `Files` (`/file`, `/download`, ...), `Sitemap`, `Robots`, `Newsletter`, `Comments`.
2. Admin routes under `/{ADMIN_PANEL_SLUG}/...`, all guarded by the `authGuard` filter (`App\Filters\AuthGuard`, registered in `app/Config/Filters.php`). Nearly everything dispatches into the single `Admin::index($page, $action, $id, $id2)` method.
3. A full duplicate of admin/image/comments routes prefixed with `/{locale}`.
4. `/cron/(:segment)/(:segment)` → `Cron::index/$module/$command`.
5. **Catch-all last:** `'/(:any)' => 'Home::index'`. Every public page URL falls through to `Home::index`, which resolves the URL to a CMS page.

### Public pages — `Home::index`
The catch-all renders CMS-managed pages. It checks the `Redirects` module first, resolves language, then uses `App\Libraries\Page` / `Link` / `Breadcrumbs` and `App\Models\PageModel` + `PageContentModel` to load a page and its content blocks. Page content blocks reference module elements; the matching module Library renders each block (see below).

### Admin panel — `Admin::index`
A monolithic dispatcher: `$page` is the admin section slug, `$action`/`$id`/`$id2` the sub-action. It enforces login (`adminLoggedIn` session key via `CheckAccess()`), builds the language switcher, and delegates section rendering. Admin sections backed by modules are resolved through `ModuleModel::getSubModulesStructure()` and the module's own `*Admin` controller.

## Module system (`modules/`)

Modules are the primary extension mechanism — each top-level dir under `modules/` (Catalog, News, Shopping, Newsletter, Gallery, Form, Survey, Event, etc.) is a self-contained feature. Each `Modules\<Name>` namespace is **manually registered** in `app/Config/Autoload.php` under `$psr4` — a new module must be added there to autoload.

Conventional module layout (mirrors the app structure):

```
modules/<Name>/
  Controllers/<Name>Admin.php   # admin-side controller, extends App\Controllers\BaseController
  Libraries/<Name>.php          # front-end render entry: index($content, $id_lang, $slug, $link)
  Libraries/CronJob.php         # optional; methods invoked by /cron/<module>/<command>
  Models/<Name>Model.php        # extends CodeIgniter\Model
  Views/admin/...               # admin screens
  Views/user/...                # public render templates
  Language/<locale>/<Name>.php  # translations, read via lang('<Name>.key')
```

How modules plug in:
- **Public rendering:** a page content block stores `id_module_element`; `Home`/`Page` instantiate `\Modules\<Name>\Libraries\<Name>` and call its `index(...)`, which returns data + a `views_dir`, rendered by the module's `Views/user/...` templates.
- **Admin:** the module's `<Name>Admin` controller is reached through the `Admin` dispatcher; it renders `Views/admin/...`.
- **Cron:** `Cron::index($module, $command)` looks up an active row in the `cron_job` DB table, then instantiates `Modules\<Name>\Libraries\CronJob` and calls `$command()` within the configured time window. Cron scheduling state lives in the database, not in code.
- Module presence/activation is also checked at runtime against the `module` DB table (`ModuleModel`), e.g. `where('slug', 'Redirects')->where('publish', 1)` plus `is_dir()`/`class_exists()` guards before use.

## App-level libraries (`app/Libraries/`)

Shared services the controllers compose (instantiated directly with `new`, not DI):
- `Page` — resolves a URL to a CMS page and assembles its content blocks.
- `Link` / `Breadcrumb` / `Breadcrumbs` / `Menu` / `Sidebar` — URL/navigation building from DB tables.
- `Assets` — collects CSS/JS lists per controller and serves them concatenated via the `/tio.css` and `/tio.js` routes (`Assets` controller). Controllers declare their asset sets in their constructor.
- `ClearCache` — cache invalidation, constructed in `Admin`'s constructor so admin actions bust caches.
- `UploadHandler` / file manager — back the `FileMenager`/`FileBrowser`/`Files` controllers and the Jodit editor integration (`JoditRestApplication`, `jodit/connector`).

### Theme images (iSense)

Static theme graphics live in `public/assets/isense/img`. The sources (PNG/JPG) are **not** served to browsers — `php spark isense:images` (`app/Commands/OptimizeIsenseImages.php`) renders WebP variants at several widths into `img/opt/` plus `opt/manifest.json`, and the front-end emits them through helpers in `app/Helpers/isense_helper.php`:

- `isense_img($src, $alt, $class, ['sizes' => …, 'loading' => …, 'fetchpriority' => …])` — full `<img>` with `srcset`/`sizes`, intrinsic `width`/`height` and `loading="lazy"` by default.
- `isense_img_url($src, $targetWidth)` — a single variant URL, for CSS `background-image`.

Both accept a bare filename, a `/assets/isense/img/...` path or a CMS URL; anything outside the manifest falls through to the original URL unchanged. After adding or replacing a source graphic, re-run `php spark isense:images` and commit `img/opt/`.

## Conventions

- Controllers extend `App\Controllers\BaseController` (auto-loads the `website` helper). Module controllers also extend it but set up `request`/`response`/`session` manually in their constructor.
- DB access is frequently done through the query builder off a model's `->db->table(...)` rather than model methods, with explicit joins and locale `WHERE id_lang = ...` filters — most content tables have a parallel `*_lang` table for translations.
- Models extend `CodeIgniter\Model` with `$table` + `$allowedFields`.
- Translations: `lang('File.key')` with files in `app/Language/<locale>/` and `modules/<Name>/Language/<locale>/`.
- `writable/` holds cache, logs, sessions, uploads (sessions use the `FileHandler`). Treat it as runtime-generated.
