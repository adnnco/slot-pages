# Translation Files

This directory contains the translation files for the Slot Pages plugin.

## File Structure

- `slot-pages.pot`: The template file containing all translatable strings
- `slot-pages-tr_TR.po`: The Turkish translation file
- `slot-pages-tr_TR.mo`: The compiled Turkish translation file
- `slot-pages-es_ES.po`: The Spanish translation file
- `slot-pages-es_ES.mo`: The compiled Spanish translation file

## Generating .mo Files

The `.mo` files in this directory are placeholders and need to be properly generated for translations to work. You can generate them using one of the following methods:

### Using WP-CLI

If you have WP-CLI installed, you can run the following commands from the plugin directory:

```bash
wp i18n make-mo languages/slot-pages-tr_TR.po languages/
wp i18n make-mo languages/slot-pages-es_ES.po languages/
```

### Using msgfmt

If you have the GNU gettext tools installed, you can run:

```bash
msgfmt -o languages/slot-pages-tr_TR.mo languages/slot-pages-tr_TR.po
msgfmt -o languages/slot-pages-es_ES.mo languages/slot-pages-es_ES.po
```

### Using Tools

You can also use tools to convert .po files to .mo files:

1. Visit a website like [poedit.net](https://poedit.net/download)
2. Download and install Poedit
3. Open the .po file in Poedit
4. Save the file, which will automatically generate the corresponding .mo file
5. Ensure the .mo file is saved in the same directory as the .po file
6. Repeat for each language file
7. After generating the .mo files, ensure they are named correctly (e.g., `slot-pages-tr_TR.mo` for Turkish and `slot-pages-es_ES.mo` for Spanish).
8. Upload the .mo files to the `languages` directory of the plugin

## Troubleshooting

If translations are not working after generating the .mo files:

1. Make sure the .mo files are in the correct location
2. Verify that the WordPress language is set correctly in Settings > General
3. Clear any caching plugins that might be caching the translations
4. Deactivate and reactivate the plugin