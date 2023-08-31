<?php

return [
    'list'                      => 'لیست سفارشات',
    'detail'                    => 'جزئیات سفارش',
    'BUY'                       => 'خرید',
    'SELL'                      => 'فروش',
    'buy'                       => 'خرید',
    'sell'                      => 'فروش',
    'symbol'                    => 'نماد',
    'side'                      => 'طرف سفارش',
    'type'                      => 'نوع سفارش',
    'base_qty'                  => 'تعداد نماد',
    'price'                     => 'قیمت نماد',
    'status'                    => 'وضعیت',
    'createdAt'                 => 'تاریخ ثبت',

    'orderCreatedSuccessfully'  => 'سفارش با موفقیت ثبت شد',

    // validation messages
    'symbolDoesNotExist'        => 'نماد :symbol وارد شده وجود ندارد.',
    'symbolServerError'         => 'مشکلی در اعتبار سنجی کردن نماد وجود دارد. لطفا دوباره تلاش کنید',
    'symbolIsRequired'          => 'وارد کردن نماد اجباری می باشد',

    'sideIsNotValid'            => 'طرف سفارش فقط می تواند خرید یا فروش باشد',
    'sideIsNotActive'           => ':side این نماد غیر فعال می باشد',
    'sideServerError'           => 'مشکلی در اعتبار سنجی کردن طرف سفارش وجود دارد. لطفا دوباره تلاش کنید',

    'typeDoesNotExist'          => ' نوع :type که وارد کردید برای این نماد پشتیبانی نمی شود ',
    'priceIsRequiredIf'         => "وقتی نوع سفارش :type می باشد، وارد کردن قیمت نماد اجباری می باشد.",

    'minQty'                    => 'تعداد وارد شده باشد بیشتر از :minQty عدد باشد',
    'maxQty'                    => 'تعداد وارد شده باشد کمتر از :maxQty عدد باشد',
    'scaleQty'                  => 'تعداد وارد شده :qty را باید به عدد :roundedQty رُند کنید',
    'baseQtyServerError'        => 'مشکلی در اعتبار سنجی کردن تعداد نماد وجود دارد. لطفا دوباره تلاش کنید',

    'minPrice'                  => 'قیمت وارد شده باشد بیشتر از :minPrice باشد',
    'maxPrice'                  => 'قیمت وارد شده باشد کمتر از :maxPrice باشد',
    'scalePrice'                => 'قیمت وارد شده :price را باید به عدد :roundedPrice رُند کنید',
    'priceServerError'          => 'مشکلی در اعتبار سنجی کردن قیمت نماد وجود دارد. لطفا دوباره تلاش کنید',


    'minNotional'               => 'مقدار حاصل ضرب تعداد در قیمت باید بیشتر از :minNotional باشد',
    'maxNotional'               => 'مقدار حاصل ضرب تعداد در قیمت باید کمتر از :maxNotional باشد',
    'scaleNotional'             => 'مقدار حاصل ضرب تعداد در قیمت :notional را به عدد :roundedNotional رُند کنید',
    'notionalServerError'       => 'مشکلی در اعتبار سنجی حاصل ضرب تعداد در قیمت به وجود آمده است',

    'priceIsOutOfRange'         => 'مقدار قیمت باید بین :downLimit و :upLimit باشد',
    'equivalentToTomans'        => 'معادل تومانی',

    'yourOrderCreatedSuccessfully' => 'سفارش  :type نماد :pair به تعداد :count با موفقیت ثبت شد',
    'yourOrderCanceledSuccessfully' => 'سفارش :type نماد :pair به تعداد :count با موفقیت لغو شد'
];
