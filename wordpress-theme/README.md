# WordPress theme: developer notes

- `redlynch-dental/`: the theme source. Its `README.md` covers installing, configuring and editing.
- `redlynch-dental-1.2.0.zip`: the installable theme, version 1.2.0 (current). Upload it in *Appearance › Themes › Add New › Upload Theme*. The theme folder is at the root of the archive.
- `redlynch-dental.zip`: the previous build (1.1.0), kept unchanged.
- `tools/build-content.js`: regenerates the theme's starter content from the static site.
- `tools/package.php`: rebuilds the ZIP.

The static site (`*.html`, `assets/`, `_build/`) is unchanged and remains the source of truth.

## Regenerating the starter content and the ZIP

Run these from the repository root after changing the static site's content in `_build/`:

```bash
node wordpress-theme/tools/build-content.js
php wordpress-theme/tools/package.php redlynch-dental-1.2.0.zip
```

Without a file name, the packager writes `redlynch-dental.zip`.

`build-content.js` reads the static site's source directly from `_build/lib.js`, `pages.js` and `services.js`, so the WordPress pages carry the same verified text. Where the finished `.html` pages were edited by hand after the build, it follows the HTML: the service articles are read from the `.html` files, and the two campaign landing pages (`cosmetic-dentistry.html`, `full-arch-dental-implants.html`) are converted from their HTML. Hand edits to other parts of the HTML pages (heroes, the homepage, About and so on) must also be made in the matching part of `build-content.js`. It writes `redlynch-dental/inc/import/content.json`.

How each part of a page is built in Elementor:

| Part of the page | Built with |
|---|---|
| Sections and layouts | Native containers with the static site's layout classes |
| Headings | Native Heading widgets |
| Paragraphs, lists, tables and figures | Native Text Editor widgets |
| Framed photos | Native Image widgets |
| Components that need the site's exact markup | Redlynch widgets (hero, cards, FAQ, hours, map, team and so on) |

The script also writes:
- the team profiles;
- the blog post;
- the menus;
- the Media Library alternative text;
- the SEO titles and descriptions, which the importer copies into ThinkRank.

The script reports any link that still points at a `.html` file, and any source or reference note that has crept into the content.

Content that has already been imported into a site is not affected by regenerating. Edit it in Elementor, or re-import with **Replace** ticked.
