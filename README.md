# Slot Pages

A WordPress plugin to manage and display slot information in a user-friendly and SEO-friendly way.

## Description

Slot Pages is a comprehensive WordPress plugin designed for websites that review or list slot games. It provides a structured way to store and display slot information with custom post types, taxonomies, and Gutenberg blocks.

The plugin creates a custom post type for slots with various meta fields to store important slot information such as rating, provider, RTP (Return to Player), minimum wager, and maximum wager. It also includes custom Gutenberg blocks to display slot information in different formats.

## Features

- **Custom Post Type**: Creates a 'slot' post type for managing slot information
- **Custom Taxonomy**: Provides a 'slot_provider' taxonomy to categorize slots by provider
- **Custom Meta Fields**:
  - Star Rating (1-5 stars)
  - Provider (taxonomy)
  - RTP (Return to Player percentage)
  - Minimum Wager
  - Maximum Wager
- **Custom Gutenberg Blocks**:
  - **Slots Grid**: Displays a grid of slots with configurable options for limit, columns, and sorting
  - **Slot Detail**: Displays detailed information about a single slot

## Requirements

- WordPress 6.4.1 or higher
- PHP 8.2 or higher

## Installation

1. Upload the `slot-pages` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Start creating slot entries from the 'Slots' menu in the WordPress admin

## Usage

### Creating Slot Entries

1. Go to 'Slots' > 'Add New' in the WordPress admin
2. Enter a title and description for the slot
3. Set a featured image (this will be used as the slot thumbnail)
4. Fill in the slot attributes in the 'Slot Attributes' meta box:
   - Rating (1-5 stars)
   - Provider
   - RTP (Return to Player percentage)
   - Minimum Wager
   - Maximum Wager
5. Publish the slot

### Managing Slot Providers

1. Go to 'Slots' > 'Providers' in the WordPress admin
2. Add, edit, or delete slot providers as needed

### Using the Slots Grid Block

1. Edit a page or post
2. Add the 'Slots Grid' block
3. Configure the block settings:
   - Limit: Number of slots to display (default: 6)
   - Columns: Number of columns in the grid (default: 2)
   - Order: Sorting order (recent or random)
4. Save the page or post

### Using the Slot Detail Block

1. Edit a slot post
2. Add the 'Slot Detail' block
3. The block will automatically display the slot's information
4. Save the post

## Customization

The plugin includes CSS files for styling the blocks:
- `assets/css/slot-admin.css`: Styles for the admin interface
- `blocks/slots-grid/style.css`: Styles for the Slots Grid block
- `blocks/slot-detail/style.css`: Styles for the Slot Detail block

You can customize these files or override the styles in your theme's CSS.

## Developer Documentation

### Plugin Structure

The plugin follows a modern object-oriented architecture with proper namespacing:

```
slot-pages/
├── assets/
│   └── css/
│       └── slot-admin.css
├── blocks/
│   ├── slot-detail/
│   │   ├── block.json
│   │   ├── index.js
│   │   ├── render.php
│   │   └── style.css
│   └── slots-grid/
│       ├── block.json
│       ├── index.js
│       ├── render.php
│       └── style.css
├── classes/
│   ├── Admin.php
│   ├── Blocks.php
│   ├── Core.php
│   ├── PostTypes.php
│   ├── Taxonomies.php
│   └── class-slot-pages.php (legacy)
├── CHANGELOG.md
├── README.md
└── slot-pages.php
```

### Class Structure

- **Core**: Main plugin class with singleton pattern that initializes all components
- **PostTypes**: Handles custom post type registration and meta fields
- **Taxonomies**: Handles taxonomy registration and term retrieval
- **Blocks**: Manages Gutenberg blocks registration and rendering
- **Admin**: Handles admin-specific functionality

### Extending the Plugin

To add new functionality:

1. Create a new class in the `classes/` directory
2. Add the class to the namespace `SlotPages`
3. Initialize the class in the `Core::__construct()` method

Example:

```php
<?php
/**
 * My custom functionality.
 *
 * @package SlotPages
 */

namespace SlotPages;

/**
 * Class for my custom functionality.
 */
class MyCustomClass {
    /**
     * Initialize hooks and actions.
     *
     * @return void
     */
    public function init(): void {
        // Add your hooks and actions here.
    }
}
```

Then update the Core class:

```php
private function __construct() {
    // Initialize components.
    $this->post_types = new PostTypes();
    $this->taxonomies = new Taxonomies();
    $this->blocks     = new Blocks();
    $this->admin      = new Admin();
    $this->my_custom  = new MyCustomClass(); // Add your class here.
}

public function init(): void {
    // Initialize components.
    $this->post_types->init();
    $this->taxonomies->init();
    $this->blocks->init();
    $this->admin->init();
    $this->my_custom->init(); // Initialize your class.
}
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a detailed list of changes.

## Support

For support or feature requests, please visit [the plugin's GitHub repository](https://github.com/adnnco/slot-pages).

## License

This plugin is licensed under the GPL v2 or later.
