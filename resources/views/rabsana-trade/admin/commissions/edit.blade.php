{{-- extend the view from master blade --}}
@extends(config('rabsana-trade.views.admin.extends'))

{{-- show the page title --}}
@section(config('rabsana-trade.views.admin.title-section'))
@lang('trade::rabsana.edit') @lang('trade::rabsana.commission')
@endsection

{{-- write custom css here --}}
@push(config('rabsana-trade.views.admin.styles-stack'))
<style>
    .hr-sect {
        display: flex;
        flex-basis: 100%;
        align-items: center;
        color: rgba(0, 0, 0, 0.35);
        margin: 8px 0px;
    }

    .hr-sect:before,
    .hr-sect:after {
        content: "";
        flex-grow: 1;
        background: rgba(0, 0, 0, 0.35);
        height: 1px;
        font-size: 0px;
        line-height: 0px;
        margin: 0px 8px;
    }
</style>
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
                            @lang('trade::rabsana.edit') @lang('trade::rabsana.commission')
                        </h4>
                    </div>

                    <div class='col-md-6 text-left'>
                        <a href="{{ route("rabsana-trade.admin-api.v1.commissions.index") }}"
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

                <form action="{{ route("rabsana-trade.admin-api.v1.commissions.update" , $commission) }}" method='POST'
                    enctype='application/x-www-form-urlencoded' class='needs-condition' novalidate>
                    @csrf
                    @method('PATCH')

                    <div class='row p-2'>

                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <ul>
                                    <li>
                                        کارمزد از هر دو طرف معامله گرفته می‌شود.
                                    </li>
                                    <li>
                                        کارمزد به صورت درصد از حجم دارایی درخواستی محاسبه می‌شود. به طور مثال اگر به
                                        عنوان فروشنده، بخواهید در برابر بیت‌کوین، ریال دریافت کنید کارمزد به صورت درصد
                                        از ریال دریافت می‌شود، و بالعکس اگر به عنوان خریدار بخواهید با ریال خود بیت‌
                                        کوین خریداری نمایید، کارمزد به صورت درصد از بیت‌کوین دریافت خواهد شد.

                                    </li>
                                    <li>
                                        در نحوه محاسبه کمیسیون به ارزی که نماد نسبت به آن خرید یا فروش می شود نیاز می
                                        باشد و انتخاب کردن آن الزامی می باشد
                                    </li>
                                    <li>
                                        درصد کارمزد خرید و فروش هر دو باید به صورت عدد باشد
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- name --}}
                        <div class='col-md-12'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='name'>
                                    @lang("trade::commission.name")
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='name' placeholder='name' name='name'
                                    value="{{ old('name' , $commission->name) }}" title="@lang('common.required')"
                                    required>
                            </div>
                        </div>

                        {{-- symbol_quote --}}
                        <div class='col-md-12'>
                            <div class='form-group mb-2'>
                                <label for='symbol_quote'>
                                    @lang('trade::commission.symbol_quote')
                                    <span class='text-danger'>*</span>
                                </label>
                                <select class='custom-select form-control' id='symbol_quote' name='symbol_quote'
                                    data-value="{{ old("symbol_quote" , $commission->symbol_quote) }}"
                                    title="@lang('common.required')" data-placeholder="@lang('common.select')" required>
                                </select>
                            </div>
                        </div>

                        {{-- taker_fee --}}
                        <div class='col-md-6'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='taker_fee'>
                                    @lang('trade::commission.fee')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='taker_fee' placeholder='fee'
                                    name='taker_fee' value="{{ old('taker_fee' , $commission->taker_fee) }}"
                                    title="@lang('common.required')" required>
                            </div>
                        </div>

                        {{-- maker_fee --}}
                        {{-- <div class='col-md-6'>
                            <div class='form-group mb-2'>
                                <label class='text-capitalize' for='maker_fee'>
                                    @lang('trade::commission.maker_fee')
                                    <span class='text-danger'>*</span>
                                </label>
                                <input type='text' class='form-control' id='maker_fee' placeholder='maker_fee'
                                    name='maker_fee' value="{{ old('maker_fee' , $commission->maker_fee) }}"
                                    title="@lang('common.required')" required>
                            </div>
                        </div> --}}

                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <ul>
                                    <li>
                                        در این قسمت شرط های تلعق کمیسیون را مشخص کنید
                                    </li>
                                    <li>
                                        به عنوان مثال :
                                        <br />
                                        دوره زمانی(دقیقه) : 1440
                                        <br />
                                        مربوط به : حجم معاملات
                                        <br />
                                        عملگر :
                                        <= <br />
                                        عملوند : 1000000
                                        <br />
                                        به عنوان مثال کاربرانی که حجم معاملات سفارشات آن ها کمتر مساوی از 1000000 باشد
                                        این درصد کمیسیون برای کارمزد ترید آن ها در نظر گرفته می شود
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- conditions --}}
                        <div id="conditions">
                            <div class="row">

                                <div id="conditionsList">

                                    @if (!empty(old('conditions' , $commission->conditions)))
                                    @foreach (old('conditions' , $commission->conditions) as $key => $item)
                                    @php
                                    $item = (object)$item;
                                    @endphp

                                    <div class="conditionItems" data-key="{{ $key }}">

                                        <div class="col-md-12 hr-sect">
                                            شرط شماره <span class="conditionIndex">{{ $key + 1 }}</span>

                                            <small class="text-danger deleteCondition">
                                                (حذف این شرط)
                                            </small>
                                        </div>

                                        {{-- period --}}
                                        <div class='col-md-6'>
                                            <div class='form-group mb-1'>
                                                <label class='text-capitalize' for='conditions[{{ $key }}][period]'>
                                                    @lang('trade::commission.period')
                                                </label>
                                                <input type='text' class='form-control'
                                                    id='conditions[{{ $key }}][period]'
                                                    name='conditions[{{ $key }}][period]' value="{{ $item->period }}" required>
                                            </div>
                                        </div>

                                        {{-- property --}}
                                        <div class='col-md-6'>
                                            <div class='form-group mb-2'>
                                                <label for='conditions[{{ $key }}][property]'>
                                                    @lang('trade::commission.property')
                                                </label>
                                                <select class='custom-select form-control hasOldProperty'
                                                    id='conditions[{{ $key }}][property]'
                                                    name='conditions[{{ $key }}][property]'
                                                    data-value="{{ $item->property }}"
                                                    data-placeholder="@lang('common.select')" required>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- operator --}}
                                        <div class='col-md-6'>
                                            <div class='form-group mb-2'>
                                                <label for='conditions[{{ $key }}][operator]'>
                                                    @lang("trade::commission.operator")
                                                </label>
                                                <select class='custom-select form-control hasOldOperators'
                                                    id='conditions[{{ $key }}][operator]'
                                                    name='conditions[{{ $key }}][operator]'
                                                    data-value="{{ $item->operator }}"
                                                    data-placeholder="@lang('common.select')" required>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- operand --}}
                                        <div class='col-md-6'>
                                            <div class='form-group mb-1'>
                                                <label class='text-capitalize' for='conditions[{{ $key }}][operand]'>
                                                    @lang('trade::commission.operand')
                                                </label>
                                                <input type='text' class='form-control'
                                                    id='conditions[{{ $key }}][operand]' value="{{ $item->operand }}"
                                                    name='conditions[{{ $key }}][operand]' required>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif

                                    @php
                                    $key = (isset($key)) ? $key + 1 : 0;
                                    @endphp


                                    <div id="sampleConditions" class="conditionItems" data-key="{{ $key }}">

                                        <div class="col-md-12 hr-sect">
                                            شرط شماره <span class="conditionIndex">{{ $key + 1 }}</span>

                                            <small class="text-danger deleteCondition">
                                                (حذف این شرط)
                                            </small>
                                        </div>

                                        <div class='col-md-6'>
                                            <div class='form-group mb-1'>
                                                <label class='text-capitalize' for='period'>
                                                    @lang('trade::commission.period')
                                                </label>
                                                <input type='text' class='form-control' id='period'
                                                    name='conditions[{{ $key }}][period]' required>
                                            </div>
                                        </div>

                                        <div class='col-md-6'>
                                            <div class='form-group mb-2'>
                                                <label for='property'>
                                                    @lang('trade::commission.property')
                                                </label>
                                                <select class='custom-select form-control' id='property'
                                                    name='conditions[{{ $key }}][property]'
                                                    data-placeholder="@lang('common.select')" required>
                                                </select>
                                            </div>
                                        </div>

                                        <div class='col-md-6'>
                                            <div class='form-group mb-2'>
                                                <label for='operator'>
                                                    @lang("trade::commission.operator")
                                                </label>
                                                <select class='custom-select form-control' id='operator'
                                                    name='conditions[{{ $key }}][operator]'
                                                    data-placeholder="@lang('common.select')" required>
                                                </select>
                                            </div>
                                        </div>

                                        <div class='col-md-6'>
                                            <div class='form-group mb-1'>
                                                <label class='text-capitalize' for='operand'>
                                                    @lang('trade::commission.operand')
                                                </label>
                                                <input type='text' class='form-control' id='operand'
                                                    name='conditions[{{ $key }}][operand]' required>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <button class="btn btn-primary" type="button" id="addNewCondition">
                                افزودن شرط جدید
                            </button>
                        </div>
                        {{-- conditions --}}


                    </div>

                    <div class="col-md-12">
                        <button class='btn btn-primary m-2' type='submit'>@lang('trade::rabsana.submit')</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<p id="getSymbolQuotesUrl" class="d-none">
    {{ route("rabsana-trade.admin-api.v1.common.symbols.quotes") }}
</p>

<p id="getCommissionProperties" class="d-none">
    {{ route("rabsana-trade.admin-api.v1.common.commissions.properties") }}
</p>

<p id="getCommissionOperators" class="d-none">
    {{ route("rabsana-trade.admin-api.v1.common.commissions.operators") }}
</p>

@endsection


{{-- write custom javascript --}}
@push(config('rabsana-trade.views.admin.scripts-stack'))

<script>
    $(function () {

        var getSymbolQuotesUrl = $("#getSymbolQuotesUrl").html().trim();
        var getCommissionProperties = $("#getCommissionProperties").html().trim();
        var getCommissionOperators = $("#getCommissionOperators").html().trim();
        commissionProperties = '';
        commissionOperators = '';


        // get the symbol quotes
        $.ajax({
            type: "GET",
            url: getSymbolQuotesUrl,
            success: function (response) {

                var options = '';
                for(var i = 0; i < response.data.length; i++) {
                    if(response.data[i] == $("#symbol_quote").data("value")){
                        selected = 'selected';
                    }else{
                        selected = '';
                    }
                    options += `<option value="${response.data[i]}" ${selected}>
                        ${response.data[i]}
                        </option>`;
                }

                $("#symbol_quote").html(options);
            }
        });

        // get the properties
        $.ajax({
            type: "GET",
            url: getCommissionProperties,
            success: function (response) {

                $.each(response.data, function (indexInArray, valueOfElement) {
                    commissionProperties += `<option value="${indexInArray}">
                        ${valueOfElement}
                        </option>`;
                });
                $("#property").html(commissionProperties);
                $.each($(".hasOldProperty"), function (indexInArray, valueOfElement) {
                    $(this).html(commissionProperties);
                    value = $(this).data('value');
                    $(this).find(`option[value=${value}]`).prop("selected" , true);
                });
            }
        });

        // get the operators
        $.ajax({
            type: "GET",
            url: getCommissionOperators,
            success: function (response) {

                $.each(response.data, function (indexInArray, valueOfElement) {
                    commissionOperators += `<option value="${indexInArray}">
                        ${valueOfElement}
                        </option>`;
                });
                $("#operator").html(commissionOperators);

                $.each($(".hasOldOperators"), function (indexInArray, valueOfElement) {
                    $(this).html(commissionOperators);
                    value = $(this).data('value');
                    $(this).find(`option[value=${value}]`).prop("selected" , true);
                });
            }
        });




        setTimeout(() => {

            // store the sampleConditions html
            var sampleConditionsElement = $('#sampleConditions');
            var sampleConditionsKey = sampleConditionsElement.data('key');
            var sampleConditions = sampleConditionsElement.html();


        // add new condition base on sampleConditions
        $('#addNewCondition').on('click', function() {

            var index = $('.conditionItems').length;
            var str = sampleConditions.replaceAll(`[${sampleConditionsKey}]`, `[${index}]`);
            var str = str.replaceAll(`<span class="conditionIndex">${sampleConditionsKey + 1}</span>`, `<span class="conditionIndex">${index + 1}</span>`);
            str = `<div class="conditionItems">${str}</div>`;
            $('#conditionsList').append(str);

        });
        }, 3000);


    // delete the condition
    $("body").delegate(".deleteCondition", "click", function() {
        $(this).closest(".conditionItems").remove();
    });


    });
</script>

@endpush
