# Moovly WP Plugin
---
## Setup
Place the entire project in the Wordpress `plugins` directory.

````
composer install && npm install
````
## Development

````
npm run dev

npm run watch
`````

## Release

* Optional: Update changelog in `readme.txt`
* Push to GitHub
* Create release on GitHub

## Installation

>In order for the installation to work, make sure your PHP environment allows file uploads of at least 20MB.

### From Source

Create a ZIP-file from the following directories or files:

````
- src/
- dist/
- vendor/
- moovly.php
`````

Upload the ZIP-file in the Wordpress plugin section

## Shortcodes

- email-campaign="1"
Launches the event `window.Moovly.emailCampaignReceived` this can be catched in the wordpress website and do something


### From Plugin Store

TO DO

