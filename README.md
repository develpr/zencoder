zencoder
========

A series of abstractions to simplify audio/video encoding with Zencoder with the Laravel 4 framework


Installation:
-------------

to install, simply add the following to your composer.json

`develpr/zencoder: "dev-l4" `


and run:

```
composer install
php artisan migrate develpr/zencoder
php artisan config:publish develpr/zencoder
```

This zencoder bundle automatically handle all routes to "zencoder/callback" and is not configureable at this time
