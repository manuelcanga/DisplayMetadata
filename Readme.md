# Display post meta, term meta, comment meta, and user meta

Contributors: trasweb

Tags: metabox, metadata, debug, custom post types, taxonomy, admin tools, custom fields, database management

Requires at least: 4.9.0

Tested up to: 6.7.0

Requires PHP: 8.1

Stable tag: 1.0.0

License: GPLv3 or later

License URI: http://www.gnu.org/licenses/gpl-3.0.html

## Summary

Displays metadata in a metabox on the creation/editing pages for posts (any CPT), terms (any taxonomy), and users.
The metadata is shown in a human-readable format, organized and unserialized. This metabox will only be visible
to administrator users or users with the `display_metadata_metabox` capability.

## Description

As a developer, you often need to access and review the values of metadata (or properties) attached to posts, terms,
users, or comments. Whether it's for debugging or optimizing custom functionalities, having a clear view of this data
is essential for ensuring your code interacts correctly with the underlying WordPress objects.

As a sysadmin, managing and cleaning up unnecessary meta fields is crucial for keeping the database lean and efficient.
By understanding which meta fields are currently in use across posts, terms, comments, and users, you can make informed
decisions about which metadata to remove, optimizing performance and preventing clutter in your system.

As a plugin author, it's important to verify that your plugin is creating and managing meta fields properly.
The Display Meta plugin allows you to inspect whether your plugin is adding the correct metadata and helps ensure 
compatibility with other plugins that may also modify metadata.

As a theme author, knowing which meta fields are available is key to leveraging them effectively in your theme designs.
Whether you want to display custom fields in a template or use metadata for styling purposes, having direct access to
this information will enable you to create more dynamic and responsive themes.

In all of these cases (and many more), the Display Meta plugin simplifies the process by displaying all relevant metadata
in a human-readable format, right within the WordPress admin interface. With this tool, you can easily view, organize,
and manage metadata without having to dig through the database or write custom queries.

## Thanks

* Codection for [reviewing](https://codection.com/plugin-para-visualizar-metadatos-en-wordpress/) this plugin.

## Installation

1. Log in to the `/wp-admin/` dashboard of your WordPress website.
2. Go to the **Plugins** section.
3. Select the **Add New** sub-menu item.
4. In the search bar, type **Display Metadata** and click the **Search Plugins** button.
5. Find the plugin and click the **Install Now** button.
6. After installation, click the **Activate Plugin** button.
7. Once activated, this plugin will add a new metabox to the editing form of posts (any CPT), terms (any taxonomy), comments, and users.

## Changelog

More detailed changes in [Display Metadata GitHub Repository](https://github.com/manuelcanga/DisplayMetadata/).

#### 1.0 / 2024-11-16

* Fixed security issue.
* Added support for recent PHP versions (starting from 8.1).
* Added unit and functional tests to the deployment workflow.
* Improved code readability.


#### 0.4 / 2022-07-25

* Fix warning with PHP 8.0
* Test with WP 6.0.1 version.
* Better meta names when keys are repeated.

#### 0.3 / 2021-03-01

* Better hover
* Better design
* Fix bug with metas with the same key.

#### 0.2 / 2021-02-05

* Support to comment metadata.
* Support to copy meta keys / values to clipboard.
* Make clickable links.
* Allow showing empty array.

#### 0.1 / 2020-12-21

* Initial release.

## Upgrade Notice

#### 1.0

Best version ever. Install it

#### 0.1

First version. Install it

#### 0.2

Support to comment metadata.