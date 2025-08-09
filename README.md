croacworks/yii2basic
======================
basic Features for yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require croacworks/yii2basic  --with-all-dependencies
```

or add

```
"croacworks/yii2basic": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, you can config the path mappings of the view component:

```php
    'modules' => [
        'common' => [ 'class' => '\croacworks\yii2basic\Module', ]
    ],
```
Up Docker

```
    docker compose -f vendor/croacworks/yii2basic/src/server/docker-compose.yml up -d
```

Run migration

```
    php yii migrate --migrationPath=@vendor/croacworks/yii2basic/src/migrations --interactive=0
```