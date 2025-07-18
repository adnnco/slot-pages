# Changelog

## Version 1.0.2

### Template System
- Added a custom template system for single slot pages
- Created a templates directory with single-slot.php template
- Added Templates class to handle template loading
- Implemented theme template override capability (theme's single-slot.php takes precedence)
- Ensured compatibility with both Gutenberg blocks and PHP templates

## Version 1.0.1

### Internationalization
- Added translation support with language files
- Added Turkish (tr_TR) translation
- Added Spanish (es_ES) translation
- Enhanced translation loading with direct `load_textdomain` calls for better compatibility
- Added validation checks for .mo files to prevent errors with placeholder files
- Added README.md with instructions for generating proper .mo files from .po files

## Version 1.0.0 (Refactored)

### Code Organization and Structure
- Added proper namespacing (`SlotPages`) to all plugin files
- Implemented a modern class structure with separate classes for different functionality:
  - `Core`: Main plugin class with a singleton pattern
  - `PostTypes`: Handles custom post-type registration and meta-fields
  - `Taxonomies`: Handles taxonomy registration and term retrieval
  - `Blocks`: Manages Gutenberg blocks registration and rendering
  - `Admin`: Handles admin-specific functionality
- Added autoloader for classes in the namespace
- Kept backward compatibility with legacy class

### Security Improvements
- Improved data validation and sanitization
- Added proper escaping for all output
- Added nonce verification for form submissions
- Added capability checks for user actions

### Performance Enhancements
- Replaced `time()` with static version number for better caching
- Improved taxonomy term retrieval with error handling
- Reduced duplicate code by centralizing meta-field definitions
- Added proper dependency management for scripts

### WordPress Coding Standards
- Added proper docblocks with `@param` and `@return` annotations
- Used type hinting and return type declarations
- Followed WordPress naming conventions
- Improved code formatting and indentation
- Added text domain for translations

### JavaScript Modernization
- Updated block JavaScript files to use modern ES6+ syntax
- Used JSX instead of createElement
- Added translation support with wp.i18n
- Added better placeholders in the editor
- Added support for wide and full alignment
- Added keywords for better discoverability

### Block Improvements
- Centralized meta field formatting
- Improved error handling for taxonomy terms
- Added helper methods for common operations
- Changed from echo to return for block output
- Added proper namespacing to block render files

### Other Improvements
- Added license information
- Added text domain and domain path for translations
- Added placeholder for future settings page
- Improved error handling throughout the plugin
