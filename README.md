Phybrid [β]
===========

A simple blog generate engine based on php and slim framework.

Installation
------------

Install composer.phar(to local directory)

``` bash
$ curl -sS https://getcomposer.org/installer | php
```

Add `"centorino/phybrid"` to your `composer.json` file:

``` json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/centorino/phybrid.git"
    }
  ],
  "require": {
    "centorino/phybrid": "*"
  },
  "scripts": {
    "post-install-cmd": [
      "cp -f ./vendor/centorino/phybrid/utils/install.php . && php install.php"
    ]
  }
}
```

And install using composer:

``` bash
$ php composer.phar install
```
Now, you can remove install.php from current directory.


Usage
-----

### Generate static html
#### generate your site
``` bash
$ php phybrid_generator.php 
```
or
``` bash
$ php phybrid_generator.php SCRIPT_NAME=(/ or /YourRootDir)
```
If your site is deployed sub directory, you should specify SCRIPT_NAME(=sub directory name).
#### unzip to your document root
``` bash
$ unzip -o ./public/site.zip -d /YourDocumentRoot
```


Configuration
-------------
please wait...


Copyright
---------

Copyright (c) 2015 [centorino](https://github.com/centorino/phybrid/blob/master/LICENSE). See LICENSE for details.
