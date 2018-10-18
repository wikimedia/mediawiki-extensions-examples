The Examples extension is a collection of small extensions that extend the BoilerPlate
extension, implementing some common features. It has many additional annotations and
inline comments explaining how it all works. Read the Examples extension, but base your own
code on the BoilerPlate extension.

If you are checking this out from Git and intend to use it, you may use the
following commands to make a clean directory of just this template;

```bash
cd extensions
```

TO-DO: Link (below) will be updated after extension rename.

```bash
git clone https://gerrit.wikimedia.org/r/p/mediawiki/extensions/examples.git
```

The commands below automates the recommended code checkers for PHP and JavaScript
code in Wikimedia projects (see https://www.mediawiki.org/wiki/Continuous_integration/Entry_points).

To take advantage of this automation:
1. Install JS's `nodejs`, `npm`, and PHP's `composer`

2. Change to the extension's directory (e.g. `cd examples`)

3. Run `npm install`

4. Run `composer install`

Once set up, running `npm test` and `composer test` will run automated code checks.
