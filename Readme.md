# Display post meta, term meta, comment meta, and user meta

Contributors: trasweb

Tags: development, metabox, debug, metadata

Requires at least: 4.9.0

Tested up to: 6.0.1

Requires PHP: 7.2

Stable tag: 0.4.0

License: GPLv3 or later

License URI: http://www.gnu.org/licenses/gpl-3.0.html

## Summary

Shows metas( fields, custom fields and protected metas ) in a metabox for posts( any CPT ), terms( any taxonomy ), comments and users. Metas are displayed for humans( organized and unserialized ). This metabox only will be displayed per either administrator users or users with `display_metadata_metabox` capability.

## Description

As a developer you normally need know about a value of some meta( or properties ) of a post, term o user.

As a sysadmin maybe you need know about which meta are created in posts/terms/comments/users in order to remove which are unnecessary.

As a plugin author you need check if you plugin is adding  meta rightly.

As a theme author you need know about which meta is available in order to can use they in your themes.

In all these cases( and others ), you can use Display Meta plugin.

## Thanks

* Codection for [reviewing](https://codection.com/plugin-para-visualizar-metadatos-en-wordpress/) this plugin.

* [Javier Esteban – @nobnob](https://profiles.wordpress.org/nobnob/) and [Yordan Soares – @yordansoares](https://profiles.wordpress.org/yordansoares/) for theirs translations.

* This plugin is inspired on [David Navia's](https://profiles.wordpress.org/davidnaviaweb/) code, [Post Meta Viewer](https://es.wordpress.org/plugins/post-meta-viewer/) and [JS Morisset's](https://profiles.wordpress.org/jsmoriss/) plugins.

## Installation

1. Login to the /wp-admin/ of your WP website.
1. Go to the *Plugins* section.
1. Select the *Add New* top sub-menu item.
1. Search for **Display Metadata** and click the *Search Plugins* button.
1. Click the *Install Now* link for this plugin.
1. Click the *Activate Plugin* button.
1. **Now, this plugin add a new metabox on editing form of posts( any CPT ), terms( any taxonomy ), comments and users**.

## Changelog
More detailed changes in [Display Metadata GitHub Repository](https://github.com/manuelcanga/DisplayMetadata/).

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

#### 0.1

First version. Install it

#### 0.2

Support to comment metadata.