{{-- extend the view from master blade --}}
@extends(config('rabsana-trade.views.admin.extends'))

{{-- show the page title --}}
@section(config('rabsana-trade.views.admin.title-section'))
@lang('trade::symbolOrder.detail')
@endsection

{{-- write custom css here --}}
@push(config('rabsana-trade.views.admin.styles-stack'))
@endpush

{{-- show the page content --}}
@section(config('rabsana-trade.views.admin.content-section'))
<div class='row'>
    <div class='col-lg-12'>
        <div class='card-box'>
            <div class='row'>
                <table class="table table-bordered table-responsive-xs table-hover w-100 table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th colspan="4" class="text-white">
                                @lang('trade::symbolOrder.detail')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                نام نماد :
                                <a href="{{ route("rabsana-trade.admin-api.v1.symbols.index" , ['search' => $order->pair])}}"
                                    target="_blank" class="text-info"">
                                    {{ $order->pair }} {{ $order->pair_name }}
                                </a>
                            </td>
                            <td>
                                طرف سفارش : 
                                <span class="
                                    {{ ($order->side_lower_case == 'buy') ? 'text-success' : 'text-danger'}}">
                                    {{ $order->side_translated }}
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                نام ثبت کننده :
                                <a href="{{ route("admin.users" , ['name' => optional($order->orderable)->name ]) }}"
                                    target="_blank" class="text-info">
                                    {{ optional($order->orderable)->name }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                تعداد سفارش : {{ $order->base_qty_prettified }} {{ $order->base }}
                                <img src="{{ $order->base_media['image']['32px']['color'] }}">
                            </td>
                            <td>
                                قیمت : {{ $order->price_prettified }} {{ $order->quote }}
                                <img src="{{ $order->quote_media['image']['32px']['color'] }}">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                تعداد وارد شده توسط کاربر :
                                {{ $order->original_base_qty_prettified }} {{ $order->base }}
                            </td>
                            <td>
                                درصد کارمزد : {{ $order->commission_percent }}
                                <br />
                                مقدار کارمزد {{ $order->commission_prettified }}
                                {{ $order->side_lower_case == 'buy' ? $order->base : $order->quote }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                تعداد پر شده : {{ $order->filled_base_qty }}
                            </td>
                            <td>
                                درصد پر شده : {{ $order->filled_percent }}%
                            </td>
                        </tr>
                        <tr>
                            <td>
                                وضعیت : {{ $order->status->name_translated }}
                            </td>
                            <td>
                                نوع : {{ $order->type->name_translated }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                تاریخ ثبت : {{ $order->jcreated_at }}
                            </td>
                            <td>
                                آخرین ویرایش : {{ $order->jupdated_at }}
                            </td>
                        </tr>
                    </tbody>
                    <thead class="bg-dark">
                        <tr>
                            <th colspan="4" class="text-white">
                                معاملاتی که این سفارش را پر کردند
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($order->side == 'BUY' && !empty($order->takers))

                        @foreach ($order->takers as $item)
                        <tr>
                            <td>
                                سفارش
                                <a href="{{ route("rabsana-trade.admin-api.v1.orders.show" , ['order' => $item->maker_order_id]) }}"
                                    target="_blank" class="text-info">
                                    #{{ $item->maker_order_id }}
                                </a>
                            </td>
                            <td>
                                تعداد پر کرده : {{ $item->taker_qty_prettified }}
                            </td>
                        </tr>
                        @endforeach

                        @elseif($order->side == 'SELL' && !empty($order->makers))

                        @foreach ($order->makers as $item)
                        <tr>
                            <td>
                                سفارش
                                <a href="{{ route("rabsana-trade.admin-api.v1.orders.show" , ['order' => $item->taker_order_id]) }}"
                                    target="_blank" class="text-info">
                                    #{{ $item->taker_order_id }}
                                </a>
                            </td>
                            <td>
                                تعداد پر کرده : {{ $item->taker_qty_prettified }}
                            </td>
                        </tr>
                        @endforeach


                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


{{-- write custom javascript --}}
@push(config('rabsana-trade.views.admin.scripts-stack'))
@endpush