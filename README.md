Phybryd [β]
===========

A php and slim framework based simple blog generate engine.

Installation
------------

Install composer.phar(to local directory)

``` bash
$ curl -sS https://getcomposer.org/installer | php
```

Add `"centorino/phybryd"` to your `composer.json` file:

``` json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/centorino/phybryd.git"
    }
  ],
  "require": {
    "centorino/phybryd": "*"
  },
  "scripts": {
    "post-install-cmd": [
      "cp -f ./vendor/centorino/phybryd/utils/install.php . && php install.php"
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
$ php phybryd_wget.php 
```
or
``` bash
$ php phybryd_wget.php SCRIPT_NAME=(/ or /YourRootDir)
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

Copyright (c) 2015 [centorino](https://github.com/centorino/phybryd/blob/master/LICENSE). See LICENSE for details.
