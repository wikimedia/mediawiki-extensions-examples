# Example extension for MediaWiki

The Examples extension is a collection of small example features that implement
common extension interfaces in MediaWiki.

The basic structure of this repository is based on the BoilerPlate extension.

## Usage

This repository is for reading, and contains verbose guidances and comments
along the way. You can freely copy snippets from here. To start your own
extension, it is recommended to copy the BoilerPlate extension instead.

## Testing

This extension implements the **[recommended entry points](https://www.mediawiki.org/wiki/Continuous_integration/Entry_points)** of Wikimedia CI for PHP and Front-end projects.

Before you can test and build code locally, you need:

* PHP 7.1, or later. (with [Composer](https://getcomposer.org/))
* [Node.js](https://nodejs.org/en/) 10, or later. (with [npm](https://nodejs.org/en/download/package-manager/))

### PHP

To run the PHP code checks and unit tests:

* Run `composer update`

This will install testing software to `vendor/` in the current directory.

Now, run `compose test` whenever you want to run the automated checks and tests.

### Front-end

To run the checks for JavaScript, JSON, and CSS:

* Run `npm install`

This will intall testing software to `node_modules/` in the current directory/

Now, run `npm test` to run the automated front-end code checks..

## Contributing

```bash
git clone https://gerrit.wikimedia.org/r/p/mediawiki/extensions/examples.git
```
