{{-- extend the view from master blade --}}
@extends(config('rabsana-trade.views.admin.extends'))

{{-- show the page title --}}
@section(config('rabsana-trade.views.admin.title-section'))
@lang('trade::symbol.list')
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

                <div class="alert alert-info">
                    <ul>
                        <li>
                            پس از تغییر اولویت دکمه اینتر را بفشارید
                        </li>
                    </ul>
                </div>

                <div class='col-md-6'>
                    <h4 class='header-title mb-2'>
                        @lang('trade::rabsana.archive')
                    </h4>
                </div>

                <div class='col-md-6 text-left'>
                    <a href='{{ route("rabsana-trade.admin-api.v1.symbols.create") }}' class='btn btn-primary'>
                        @lang('trade::rabsana.add') @lang('trade::rabsana.symbol')
                    </a>
                </div>

            </div>

            <form class='mb-2' method='get'>
                <div class='row'>

                    {{-- search --}}
                    <div class='col-md-4 mt-1'>
                        <label>@lang("trade::symbol.name")</label>
                        <input placeholder="@lang('trade::rabsana.search')" class='form-control' type='search'
                            name='search' value='{{ Request::query("search") }}' />
                    </div>

                    {{-- buy_is_active --}}
                    <div class='col-md-4 mt-1'>
                        <label>@lang('trade::symbol.buy_status')</label>
                        <select class='custom-select form-control' id='buy_is_active' name='buy_is_active'>
                            <option value='' selected>@lang('trade::rabsana.allStatus')</option>
                            <option value='1' @if(Request::query('buy_is_active')==1) selected @endif>
                                @lang("trade::rabsana.active")
                            </option>
                            <option value='0' @if(Request::query('buy_is_active')==0 &&
                                is_numeric(Request::query('buy_is_active'))) selected @endif>
                                @lang("trade::rabsana.inactive")
                            </option>
                        </select>
                    </div>

                    {{-- sell_is_active --}}
                    <div class='col-md-4 mt-1'>
                        <label>@lang('trade::symbol.sell_status')</label>
                        <select class='custom-select form-control' id='sell_is_active' name='sell_is_active'>
                            <option value='' selected>@lang('trade::rabsana.allStatus')</option>
                            <option value='1' @if(Request::query('sell_is_active')==1) selected @endif>
                                @lang("trade::rabsana.active")
                            </option>
                            <option value='0' @if(Request::query('sell_is_active')==0 &&
                                is_numeric(Request::query('sell_is_active'))) selected @endif>
                                @lang("trade::rabsana.inactive")
                            </option>
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
                            <th> # </th>
                            <th> @lang("trade::rabsana.image")</th>
                            <th> @lang('trade::symbol.base') </th>
                            <th> @lang('trade::symbol.quote') </th>
                            <th> @lang('trade::symbol.pair') </th>
                            <th> @lang('trade::symbol.base_name') </th>
                            <th> @lang('trade::symbol.quote_name') </th>
                            <th> @lang('trade::symbol.pair_name') </th>
                            <th> @lang('trade::symbol.buy_status') </th>
                            <th> @lang('trade::symbol.sell_status') </th>
                            <th> @lang('trade::symbol.priority') </th>
                            <th> @lang('trade::rabsana.tools') </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($symbols as $item)
                        <tr>
                            <th>
                                {{ $loop->iteration }}
                            </th>
                            <td>
                                <img src="{{ $item->quote_media['image']['32px']['color'] }}">
                                <img src="{{ $item->base_media['image']['32px']['color'] }}">
                            </td>
                            <td>
                                {{ $item->base }}
                            </td>
                            <td>
                                {{ $item->quote }}
                            </td>
                            <td>
                                {{ $item->pair }}
                            </td>
                            <td>
                                {{ $item->base_name }}
                            </td>
                            <td>
                                {{ $item->quote_name }}
                            </td>
                            <td>
                                {{ $item->pair_name }}
                            </td>
                            <td>
                                {!! $item->buy_is_active_prettified !!}
                            </td>
                            <td>
                                {!! $item->sell_is_active_prettified !!}
                            </td>
                            <td>
                                <form action='{{ route("rabsana-trade.admin-api.v1.symbols.change.priority", $item) }}'
                                    method="POST">
                                    @csrf

                                    <input type="number" name="priority" class="text-center"
                                        value="{{ $item->priority }}" />

                                </form>
                            </td>
                            <td>
                                <div class='btn-group'>
                                    {{--edit--}}
                                    <a href='{{ route("rabsana-trade.admin-api.v1.symbols.edit" , $item) }}'
                                        class='btn btn-sm btn-success' data-toggle='tooltip'
                                        title="@lang('trade::rabsana.edit')">
                                        <span class='fas fa-edit'></span>
                                    </a>

                                    {{--destroy--}}
                                    <form action='{{ route("rabsana-trade.admin-api.v1.symbols.destroy" , $item) }}'
                                        method="POST">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class='btn btn-sm btn-danger' data-toggle='tooltip'
                                            title="@lang('trade::rabsana.delete')">
                                            <span class='fas fa-times'></span>
                                        </button>
                                    </form>
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
                {{ $symbols->onEachSide(7)->links() }}
            </div>
        </div>
    </div>
</div>
@endsection


{{-- write custom javascript --}}
@push(config('rabsana-trade.views.admin.scripts-stack'))
@endpush