Content prototyping module for Yii 2.0 Framework
================================================
Backend UI for static files

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


Usage
-----

*TBD*

### Twig example

    {{ use ('hrzg/moxiecode/moxiemanager/widgets') }}
    
    {{ browse_button_widget( {"tagName": "a"} ) }}



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
        
