<?php

return [
    'list'                      => 'Orders List',
    'detail'                    => 'Order Detail',
    'BUY'                       => 'Buy',
    'SELL'                      => 'Sell',
    'buy'                       => 'Buy',
    'sell'                      => 'Sell',
    'symbol'                    => 'Symbol',
    'side'                      => 'Order Side',
    'type'                      => 'Order Type',
    'base_qty'                  => 'Symbol Quantity',
    'price'                     => 'Symbol Price',
    'status'                    => 'status',
    'createdAt'                 => 'Created At',

    'orderCreatedSuccessfully'  => 'Order has successfully created',

    // validation messages
    'symbolDoesNotExist'        => 'The :symbol, you have entered, does not exist',
    'symbolServerError'         => 'There is a problem in validating the symbol. Please try again.',
    'symbolIsRequired'          => 'The symbol is required',

    'sideIsNotValid'            => 'The order side can be BUY or SELL',
    'sideIsNotActive'           => ':side' . 'ing this symbol is disabled',
    'sideServerError'           => 'There is a problem in validating the side. Please try again.',
    
    'typeDoesNotExist'          => 'the :type, you have entered, does not supported for this symbol',
    'priceIsRequiredIf'         => 'When the order type is :type, the price is required',
    
    'minQty'                    => 'The quantity you have entered must be greater than :minQty',
    'maxQty'                    => 'The quantity you have entered must be less than :maxQty',
    'scaleQty'                  => 'Round the quantity :qty to :roundedQty',
    'baseQtyServerError'        => 'There is a problem in validating the symbol quantity. Please try again.',
    
    'minPrice'                  => 'The price you have entered must be greater than :minPrice',
    'maxPrice'                  => 'The price you have entered must be less than :maxPrice',
    'scalePrice'                => 'Round the price :price to :roundedPrice',
    'priceServerError'          => 'There is a problem in validating the symbol price. Please try again.',
    
    'minNotional'               => 'The price × quantity amount must be greater than :minNotional',
    'maxNotional'               => 'The price × quantity amount must be less than :maxNotional',
    'scaleNotional'             => 'Round the price × quantity amount :notional to :roundedNotional',
    'notionalServerError'       => 'There is a problem in validating the price × quantity amount. Please try again.',


    'priceIsOutOfRange'         => 'The price amount must be between :downLimit and :upLimit',
    'equivalentToTomans'        => 'equivalent to Tomans',

    'yourOrderCreatedSuccessfully' => ':type order with pair :pair and count :count has successfully created',
    'yourOrderCanceledSuccessfully' => ':type order with pair :pair and count :count has successfully canceled'
];
