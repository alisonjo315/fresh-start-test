# cd-events-pull-wp-plugin

Pulls events from the CU Calendar [Localist API](https://developer.localist.com/doc/api) and saves it to a WordPress custom content type.
Use the settings tab to controll how data is pulled, filtered, and loaded into WP custom content fields.

install with composer

```json
...
    {
        "type": "package",
        "package": {
            "name": "cubear/cd-events-pull-wp-plugin",
            "version": "dev-master",
            "type": "wordpress-plugin",
            "dist": {
            "type": "zip",
            "url": "https://github.com/CU-CommunityApps/cd-events-pull-wp-plugin/archive/master.zip"
            }
        }
    }
...
    "require": {
        "composer/installers": "^1.9",
        "cubear/cd-events-pull-wp-plugin": "dev-master"
    },
```

## Typical settings for WP custom content fields

This plugin is usually used with custom content types.

You can find typical Advanced Custom Fields and Custom Post Type UI settings in [/docs/events-ct.php](/docs/events-ct.php).) 

Install and enable the following plugins:

- Advanced Custom Fields
- Custom Post Type UI

I also recommend you install and enable the optional plugin WP Crontrol

copy /docs/events-ct.php to your theme root directory

in your theme functions.php add

```php
require_once( dirname( __FILE__ ) . '/events-ct.php' );
```

this will load the events-ct.php file and configure the custom content type.

next enable the CD Events Pull plugin

and configure the settings page `/wp-admin/admin.php?page=cd-events-pull-wp-plugin-settings`

Then configure the settings as in the screenshot below.

![events-pull-settings](/docs/events-pull-settings.png)

then run the following cron job `lando wp cron event run cd_events_pull_cron_hook`

Then you should see the events in the custom events content list.

## Sites using this plugin.
