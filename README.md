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

* Update version in `moovly.php`
* run `npm run production`
* push to remote to start build flow (not ready yet)

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

### From Plugin Store

TO DO
