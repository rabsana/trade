{{-- extend the view from master blade --}}
@extends(config('rabsana-trade.views.admin.extends'))

{{-- show the page title --}}
@section(config('rabsana-trade.views.admin.title-section'))
@lang('trade::rabsana.edit') @lang('trade::rabsana.symbol')
@endsection

{{-- write custom css here --}}
@push(config('rabsana-trade.views.admin.styles-stack'))
@endpush

{{-- show the page content --}}
@section(config('rabsana-trade.views.admin.content-section'))

<div class='row'>
    <div class='col-lg-12'>
        <div class='card'>
            <div class='card-body'>
                <div class='row p-2'>
                    <div class='col-md-6'>
                        <h4 class='header-title text-capitalize'>
                            @lang('trade::rabsana.edit') @lang('trade::rabsana.symbol')
                        </h4>
                    </div>

                    <div class='col-md-6 text-left'>
                        <a href="{{ route('rabsana-trade.admin-api.v1.symbols.index') }}"
                            class='btn btn-primary'>@lang('trade::rabsana.archive')</a>
                    </div>

                </div>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $item)
                        <li>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('rabsana-trade.admin-api.v1.symbols.update' , $symbol) }}" method='POST'
                    enctype='application/x-www-form-urlencoded' class='needs-validation' novalidate>
                    @csrf
                    @method('PATCH')
                    <div class='row p-2'>

                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <ul>
                                    <li>
                                        اطلاعات مربوط به نماد را به صورت انگلیسی بنویسید. مانند BTC, ETH, USDT, TOMAN,
                                        BTCTOMAN,...
                                    </li>
                                    <li>
                                        اطلاعات مربوط به نام را میتوانید به صورت فارسی بنویسید. مانند بیت کوین، اتر،
                                        تتر،
                                        تومان، بیت کوین / تتر، ...
                                    </li>
                                    <li>
                                        در صورت غیر فعال بودن هر دو وضعیت خرید و فروش، نماد در مارکت نمایش داده نخواهد
                                        شد
                                    </li>
                                    <li>
                                        حداقل یک نوع سفارش گیری برای نماد مشخص کنید
                                    </li>
                                    <li class="text-danger">
                                        نماد ها را بر اساس علامت اختصاری ارز های الکترونیکی وارد کنید و از وارد کردن
                                        نماد های نامعتبر خودداری کنید
                                    </li>
                                    <li class="text-danger">
                                        از آنجای که محاسبات صرافی به صورت تومانی می باشد از وارد کردن TOMAN,IRR خودداری
                                        کنید و حتما ارز دوم را TOMAN وارد کنید
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- base --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='base'>
                                    @lang('trade::symbol.base')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='base' placeholder='BTC,ETH,...' name='base'
                                    value="{{ old('base' , $symbol->base) }}" title="@lang('trade::rabsana.required')"
                                    required>
                            </div>
                        </div>

                        {{-- quote --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='quote'>
                                    @lang('trade::symbol.quote')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='quote' placeholder='USDT,TOMAN,...'
                                    name='quote' value="{{ old('quote' , $symbol->quote) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- pair --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='pair'>
                                    @lang('trade::symbol.pair')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='pair' placeholder='BTCUSDT,ETHUSDT,...'
                                    name='pair' value="{{ old('pair' , $symbol->pair) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- base_name --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='base_name'>
                                    @lang('trade::symbol.base_name')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='base_name' placeholder='بیت کوین، اتر, ...'
                                    name='base_name' value="{{ old('base_name' , $symbol->base_name) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- quote_name --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='quote_name'>
                                    @lang('trade::symbol.quote_name')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='quote_name' placeholder='تتر، تومان، ...'
                                    name='quote_name' value="{{ old('quote_name' , $symbol->quote_name) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- pair_name --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='pair_name'>
                                    @lang('trade::symbol.pair_name')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='pair_name' placeholder='بیت کوین / تتر'
                                    name='pair_name' value="{{ old('pair_name' , $symbol->pair_name) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- buy_is_active --}}
                        <div class='col-md-6'>
                            <div class='form-group mb-2'>
                                <label for='buy_is_active'>
                                    @lang('trade::symbol.buy_status')
                                    <span class='text-danger'>*</span>
                                </label>
                                <select class='custom-select form-control' id='buy_is_active' name='buy_is_active'
                                    required>
                                    <option value='1' @if(old('buy_is_active' , $symbol->buy_is_active)==1) selected
                                        @endif>
                                        @lang('trade::rabsana.active')
                                    </option>
                                    <option value='0' @if(old('buy_is_active' , $symbol->buy_is_active)==0) selected
                                        @endif>
                                        @lang('trade::rabsana.inactive')
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- sell_is_active --}}
                        <div class='col-md-6'>
                            <div class='form-group mb-2'>
                                <label for='sell_is_active'>
                                    @lang('trade::symbol.sell_status')
                                    <span class='text-danger'>*</span>
                                </label>
                                <select class='custom-select form-control' id='sell_is_active' name='sell_is_active'
                                    required>
                                    <option value='1' @if(old('sell_is_active' , $symbol->sell_is_active)==1) selected
                                        @endif>
                                        @lang('trade::rabsana.active')
                                    </option>
                                    <option value='0' @if(old('sell_is_active' , $symbol->sell_is_active)==0) selected
                                        @endif>
                                        @lang('trade::rabsana.inactive')
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- types --}}
                        <div class='col-md-12'>
                            <div class='form-group mb-2'>
                                <label for='types'>
                                    @lang('trade::symbol.types')
                                    <span class='text-danger'>*</span>
                                </label>
                                <select class='custom-select form-control select2' id='types' name='types[]' required
                                    multiple>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <ul>
                                    <li>
                                        با وارد کردن اعتبار سنجی های زیر می توانید تعداد، قیمت و نحوه ثبت سفارش را کنترل
                                        کنید
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- min_qty --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='min_qty'>
                                    @lang('trade::symbol.min_qty')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='min_qty' placeholder='min_qty'
                                    name='min_qty' value="{{ old('min_qty' , $symbol->validation->min_qty) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- max_qty --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='max_qty'>
                                    @lang('trade::symbol.max_qty')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='max_qty' placeholder='max_qty'
                                    name='max_qty' value="{{ old('max_qty' , $symbol->validation->max_qty) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- scale_qty --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='scale_qty'>
                                    @lang('trade::symbol.scale_qty')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='scale_qty' placeholder='scale_qty'
                                    name='scale_qty' value="{{ old('scale_qty' , $symbol->validation->scale_qty) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- min_price --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='min_price'>
                                    @lang('trade::symbol.min_price')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='min_price' placeholder='min_price'
                                    name='min_price' value="{{ old('min_price' , $symbol->validation->min_price) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- max_price --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='max_price'>
                                    @lang('trade::symbol.max_price')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='max_price' placeholder='max_price'
                                    name='max_price' value="{{ old('max_price' , $symbol->validation->max_price) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- scale_price --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='scale_price'>
                                    @lang('trade::symbol.scale_price')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='scale_price' placeholder='scale_price'
                                    name='scale_price'
                                    value="{{ old('scale_price' , $symbol->validation->scale_price) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- min_notional --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='min_notional'>
                                    @lang('trade::symbol.min_notional')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='min_notional' placeholder='min_notional'
                                    name='min_notional'
                                    value="{{ old('min_notional' , $symbol->validation->min_notional) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- max_notional --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='max_notional'>
                                    @lang('trade::symbol.max_notional')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='max_notional' placeholder='max_notional'
                                    name='max_notional'
                                    value="{{ old('max_notional' , $symbol->validation->max_notional) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        {{-- scale_notional --}}
                        <div class='col-md-4'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='scale_notional'>
                                    @lang('trade::symbol.scale_notional')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='scale_notional' placeholder='scale_notional'
                                    name='scale_notional'
                                    value="{{ old('scale_notional' , $symbol->validation->scale_notional) }}"
                                    title="@lang('trade::rabsana.required')" required>
                            </div>
                        </div>

                        <div class="col-md-12"">
                            <div class=" alert alert-info">
                            <ul>
                                <li>
                                    برای تنظیم کردن دامنه نوسان متحرک این مقادیر باید وارد شود
                                </li>
                                <li>
                                    با استفاده از این اعتبار سنجی ها می توانید قیمت سفارشات رو کنترل کنید که از میانگین
                                    بازار
                                    دور نباشند در غیر اینصورت قیمت توسط کاربران در مدت زمان کوتاهی می تواند خیلی بالا و
                                    خیلی پایین برود و بازار تعادل نخواهد داشت
                                </li>
                                <li>
                                    میانگین قیمت سفارشات در دقیقه:
                                    <br />
                                    مثال 1 : 30 دقیقه. مرکز دامنه نوسان می شود میانگین قیمت سفارشات در 30 دقیقه گذشته
                                    <br />
                                    مثال 2 : 0 دقیقه. مرکز دامنه نوسان می شود قیمت آخرین سفارش
                                </li>
                                <li>
                                    حداکثر درصد قیمت از بالا مقداری می باشد که قیمت می تواند از مرکز نوسان به سمت بالا
                                    دور شود
                                </li>
                                <li>
                                    حداکثر درصد قیمت از پایین مقداری می باشد که قیمت می تواند از مرکز نوسان به سمت پایین
                                    دور
                                    شود
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- percent_order_price_up --}}
                    <div class='col-md-4'>
                        <div class='form-group mb-2'>
                            <label class='text-capitalize' for='percent_order_price_up'>
                                @lang('trade::symbol.percent_order_price_up')
                                <span class='text-danger'>*</span>
                            </label>
                            <input type='text' class='form-control' id='percent_order_price_up'
                                placeholder='percent_order_price_up' name='percent_order_price_up'
                                value="{{ old('percent_order_price_up', $symbol->validation->percent_order_price_up) }}"
                                title="@lang('trade::rabsana.required')" required>
                        </div>
                    </div>

                    {{-- percent_order_price_down --}}
                    <div class='col-md-4'>
                        <div class='form-group mb-2'>
                            <label class='text-capitalize' for='percent_order_price_down'>
                                @lang('trade::symbol.percent_order_price_down')
                                <span class='text-danger'>*</span>
                            </label>
                            <input type='text' class='form-control' id='percent_order_price_down'
                                placeholder='percent_order_price_down' name='percent_order_price_down'
                                value="{{ old('percent_order_price_down' , $symbol->validation->percent_order_price_down) }}"
                                title="@lang('trade::rabsana.required')" required>
                        </div>
                    </div>

                    {{-- percent_order_price_minute --}}
                    <div class='col-md-4'>
                        <div class='form-group mb-2'>
                            <label class='text-capitalize' for='percent_order_price_minute'>
                                @lang('trade::symbol.percent_order_price_minute')
                                <span class='text-danger'>*</span>
                            </label>
                            <input type='text' class='form-control' id='percent_order_price_minute'
                                placeholder='percent_order_price_minute' name='percent_order_price_minute'
                                value="{{ old('percent_order_price_minute' , $symbol->validation->percent_order_price_minute) }}"
                                title="@lang('trade::rabsana.required')" required>
                        </div>
                    </div>

                    {{-- average_price_source_is_market --}}
                    <div class='col-md-6'>
                        <div class='form-group mb-2'>
                            <label for='average_price_source_is_market'>
                                منبع محاسبه میانگین قیمت بازار داخلی باشد؟
                                <span class='text-danger'>*</span>
                            </label>
                            <select class='custom-select form-control' id='average_price_source_is_market'
                                name='average_price_source_is_market' required>
                                <option value='1' @if(old('average_price_source_is_market', $symbol->validation->average_price_source_is_market )==1) selected @endif>
                                    بله
                                </option>
                                <option value='0' @if(old('average_price_source_is_market', $symbol->validation->average_price_source_is_market)==0) selected @endif>
                                    خیر
                                </option>
                            </select>
                        </div>
                    </div>

            </div>

            <div class="col-md-12">
                <button class='btn btn-primary mt-3' type='submit'>@lang('trade::rabsana.submit')</button>
            </div>

            </form>
        </div>
    </div>
</div>
</div>

<p class="d-none" id="symbolTypes">
    {{ implode(',' , collect($symbol->types)->pluck('id')->toArray()) }}
</p>

<p class="d-none" id="getOrderTypesUrl">
    {{ route("rabsana-trade.admin-api.v1.order-types.index") }}
</p>

@endsection


{{-- write custom javascript --}}
@push(config('rabsana-trade.views.admin.scripts-stack'))

<script>
    $(function(){

    var symbolTypes = $('#symbolTypes').html().trim();
    var url = $("#getOrderTypesUrl").html().trim();

    $.ajax({
        type: "GET",
        url: url,
        success: function (response) {
            var options = '';
            var types = response.data;
            for(var i = 0 ; i < types.length ; i++){
                var selected = '';
                if(symbolTypes.includes(types[i].id)){
                    selected = 'selected';
                }
                options += `<option value="${types[i].id}" ${selected} >${types[i].name_translated}</option>`;
            }
            $('#types').html(options);
        }
    });

});

</script>

@endpush