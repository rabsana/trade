{{-- extend the view from master blade --}}
@extends(config('rabsana-trade.views.admin.extends'))

{{-- show the page title --}}
@section(config('rabsana-trade.views.admin.title-section'))
@lang('trade::symbolOrder.list')
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

                <div class='col-md-6'>
                    <h4 class='header-title mb-2'>
                        @lang('trade::rabsana.archive')
                    </h4>
                </div>
            </div>

            <form class='mb-2' method='get'>
                <div class='row'>

                    {{-- search --}}
                    <div class='col-md-4 mt-1'>
                        <label>@lang("trade::rabsana.search")</label>
                        <input placeholder="@lang('trade::rabsana.search')" class='form-control' type='search'
                            name='search' value='{{ Request::query('search') }}' />
                    </div>

                    {{-- user --}}
                    <div class='col-md-4 mt-1'>
                        <label>کاربر</label>
                        <input placeholder="کاربر" class='form-control' type='search' name='user'
                            value='{{ Request::query('user') }}' />
                    </div>

                    {{-- side --}}
                    <div class='col-md-4 mt-1'>
                        <label for='side'>
                            @lang('trade::symbolOrder.side')
                        </label>
                        <select class='custom-select form-control select2' id='side' name='side'>
                            <option value="" @if(!Request::query('side')) selected @endif>
                                @lang('trade::rabsana.allStatus')
                            </option>
                            <option value="buy" @if(Request::query('side')=='buy' ) selected @endif>
                                @lang('trade::symbolOrder.buy')
                            </option>
                            <option value="sell" @if(Request::query('side')=='sell' ) selected @endif>
                                @lang('trade::symbolOrder.sell')
                            </option>
                        </select>
                    </div>

                    {{-- statuses --}}
                    <div class='col-md-4 mt-1'>
                        <label for='statuses'>
                            @lang('trade::symbol.statuses')
                        </label>
                        <select class='custom-select form-control select2' id='statuses' name='statusId'>
                        </select>
                    </div>

                    {{-- types --}}
                    <div class='col-md-4 mt-1'>
                        <label for='types'>
                            @lang('trade::symbol.types')
                        </label>
                        <select class='custom-select form-control select2' id='types' name='typeId'>
                        </select>
                    </div>

                    <div class='col-md-12 mt-1'>
                        <button class='btn btn-primary' type='submit'> @lang('trade::rabsana.search') </button>
                    </div>
                </div>
            </form>

            <div class='table-responsive'>
                <table class='table mb-0 table-responsive-xs table-striped'>
                    <thead>
                        <tr>
                            <th> ID </th>
                            <th> @lang("trade::rabsana.image")</th>
                            <th> @lang('trade::symbol.pair') </th>
                            <th> کاربر </th>
                            <th> @lang('trade::symbolOrder.side') </th>
                            <th> @lang('trade::symbolOrder.base_qty') </th>
                            <th> @lang('trade::symbolOrder.price') </th>
                            <th> @lang('trade::symbolOrder.equivalentToTomans') </th>
                            <th> @lang('trade::symbolOrder.status') </th>
                            <th> @lang('trade::symbolOrder.type') </th>
                            <th> @lang('trade::symbolOrder.createdAt') </th>
                            <th> @lang('trade::rabsana.tools') </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $item)
                        <tr>
                            <th>
                                #{{ $item->id }}
                            </th>
                            <td>
                                <img src="{{ $item->quote_media['image']['32px']['color'] }}">
                                <img src="{{ $item->base_media['image']['32px']['color'] }}">
                            </td>
                            <td>
                                <a href="{{ route("rabsana-trade.admin-api.v1.symbols.index" , ['search' => $item->pair]) }}"
                                    target="_blank" class="text-info">
                                    {{ $item->pair }} <br />
                                    {{ $item->pair_name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route("admin.users" , ['name' => optional($item->orderable)->name ]) }}"
                                    target="_blank" class="text-info">
                                    {{ optional($item->orderable)->name }}
                                </a>
                            </td>
                            <td>
                                <span class="{{ ($item->side_lower_case == 'buy') ? 'text-success' : 'text-danger'}}">
                                    {{ $item->side_translated }}
                                </span>
                            </td>
                            <td>
                                {{ $item->base_qty_prettified }} {{ $item->base }}
                            </td>
                            <td>
                                {{ $item->price_prettified }} {{ $item->quote }}
                            </td>
                            <td>
                                {{ number_format($item->equivalent_to_tomans }}
                            </td>
                            <td>
                                {{ $item->status->name_translated }}
                            </td>
                            <td>
                                {{ $item->type->name_translated }}
                            </td>
                            <td>
                                {{ $item->jcreated_at }}
                            </td>
                            <td>
                                <div class='btn-group'>
                                    {{--edit--}}
                                    <a href='{{ route("rabsana-trade.admin-api.v1.orders.show" , $item) }}'
                                        class='btn btn-sm btn-success' data-toggle='tooltip'
                                        title="@lang('trade::rabsana.detail')">
                                        <span class='fas fa-eye'></span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class='alert-danger text-center noResult' colspan="20">@lang('trade::rabsana.noResult')
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $orders->onEachSide(7)->links() }}
            </div>
        </div>
    </div>
</div>


<p class="d-none" id="statusId">
    {{ Request::query('statusId') }}
</p>
<p class="d-none" id="getOrderStatusesUrl">
    {{ route("rabsana-trade.admin-api.v1.order-statuses.index") }}
</p>

<p class="d-none" id="typeId">
    {{ Request::query('typeId') }}
</p>
<p class="d-none" id="getOrderTypesUrl">
    {{ route("rabsana-trade.admin-api.v1.order-types.index") }}
</p>

@endsection


{{-- write custom javascript --}}
@push(config('rabsana-trade.views.admin.scripts-stack'))

<script>
    $(function(){

    var statusId = $('#statusId').html().trim();
    var getOrderStatusesUrl = $("#getOrderStatusesUrl").html().trim();

    $.ajax({
        type: "GET",
        url: getOrderStatusesUrl,
        success: function (response) {
            var options = '';
            var statuses = response.data;
            for(var i = 0 ; i < statuses.length ; i++){
            var selected = '';
            if(statuses[i].id == statusId){
                selected = 'selected';
            }
            options += `<option value="${statuses[i].id}" ${selected} >${statuses[i].name_translated}</option>`;
         }
            $('#statuses').html(options);
        }
    });

    var typeId = $('#typeId').html().trim();
    var getOrderTypesUrl = $("#getOrderTypesUrl").html().trim();

    $.ajax({
        type: "GET",
        url: getOrderTypesUrl,
        success: function (response) {
            var options = '';
            var types = response.data;
            for(var i = 0 ; i < types.length ; i++){
            var selected = '';
            if(types[i].id == typeId){
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