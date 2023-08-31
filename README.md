# Trade system


The `rabsana/trade` package provides some endpoints to add,edit,show,delete the symbols like `BTCUSDT`,`ETHUSDT`,... and add buy/sell orders to trade them


# Installation

1- install the package:
```php
composer require rabsana/trade
```

2- publish all resources:
```php
php artisan vendor:publish --tag=rabsana-trade-publish-all
```

Or you can publish resources seprately:
```php
php artisan vendor:publish --tag=rabsana-trade-migrations
php artisan vendor:publish --tag=rabsana-trade-config
php artisan vendor:publish --tag=rabsana-trade-assets
php artisan vendor:publish --tag=rabsana-trade-langs
php artisan vendor:publish --tag=rabsana-trade-views
```

3- run the migrations
```php
php artisan migrate
```

4- download the package's postman collection: [Postman link](https://www.getpostman.com/collections/0c9b6493ad53d61f42e5)


### There are two groups of endpoints: 1- api (user side) 2- adminApi (admin side)
### At the `rabsana-trade` config file you can set the middlewares that protects the private endpoints otherwise everyone can `CRUD` the symbols,orders,..


# Match the orders

After the orders created we have to match them. to do that run the `php artisan order:match` command
this command has two argument
1- the first one is the number of orders that be matched. the default is `100`
2- the second argument is the name of symbol to match the a specific symbol's order. the default value is `NULL` = match all symbols


# generate random orders

There is a command which generate orders in `BTCUSDT`,`ETHUSDT`,`BNBUSDT` symbols. please run this command in local environment

`php artisan order:generate`

this command will generate 1000 orders. you can send the number of orders as the first argument

