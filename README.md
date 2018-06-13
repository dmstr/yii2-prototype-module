Yii2 Prototype Module
===========================

[![Latest Stable Version](https://poser.pugx.org/dmstr/yii2-prototype-module/v/stable.svg)](https://packagist.org/packages/dmstr/yii2-prototype-module) 
[![Total Downloads](https://poser.pugx.org/dmstr/yii2-prototype-module/downloads.svg)](https://packagist.org/packages/dmstr/yii2-prototype-module)
[![License](https://poser.pugx.org/dmstr/yii2-prototype-module/license.svg)](https://packagist.org/packages/dmstr/yii2-prototype-module)


Twig, LESS and HTML Content prototyping module for Yii 2.0 Framework

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist dmstr/yii2-prototype-module "*"
```

or add

```
"dmstr/yii2-prototype-module": "*"
```

to the require section of your `composer.json` file.

Requirements
------------

- configured Twig view renderer in application (since 0.5.0-rc6)

Usage
-----

####Prototype command:

#####Configuration

In your console config add:

```
'controllerMap' => [
    'prototype' => 'dmstr\modules\prototype\commands'
]
```

##### Commands:

- prototype/export-html
- prototype/export-less
- prototype/export-twig

Each of these commands exports either html, less or twig as a file on a given file path (via `--exportPath` flag) default is `@runtime/exports`

Note: To escape file names you can use the `--escapeFileNames` flag


### Twig example

    {{ use ('hrzg/moxiecode/moxiemanager/widgets') }}
    
    {{ browse_button_widget( {"tagName": "a"} ) }}

### Cache trigger time

    \Yii::$app->cache->get('prototype.less.changed_at');

Testing
-------

    docker-compose up -d
    
    docker-compose run phpfpm codecept run
    
    
CRUDS
-----

:bangbang: Do no regenerate CRUDs for `html`

    $ yii batch \
        --tables=app_twig \
        --modelNamespace=dmstr\\modules\\prototype\\models \
        --modelQueryNamespace=dmstr\\modules\\prototype\\models\\query \
        --crudSearchModelNamespace=dmstr\\modules\\prototype\\models\\query \
        --crudControllerNamespace=dmstr\\modules\\prototype\\controllers \
        --crudViewPath=@dmstr/modules/prototype/views \
        
