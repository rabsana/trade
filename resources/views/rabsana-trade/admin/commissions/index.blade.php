{{-- extend the view from master blade --}}
@extends(config('rabsana-trade.views.admin.extends'))

{{-- show the page title --}}
@section(config('rabsana-trade.views.admin.title-section'))
@lang('trade::commission.list')
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

                <div class='col-md-6 text-left'>
                    <a href='{{ route("rabsana-trade.admin-api.v1.commissions.create") }}' class='btn btn-primary'>
                        @lang('trade::rabsana.add') @lang('trade::rabsana.commission')
                    </a>
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
                            <th> @lang("trade::commission.name") </th>
                            <th> @lang("trade::commission.taker_fee") </th>
                            <th> @lang("trade::commission.maker_fee") </th>
                            <th> @lang("trade::commission.symbol_quote") </th>
                            <th> @lang('trade::rabsana.tools') </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $item)
                        <tr>
                            <th>
                                {{ $loop->iteration }}
                            </th>
                            <td>
                                {{ $item->name }}
                            </td>
                            <td>
                                {{ $item->taker_fee }}
                            </td>
                            <td>
                                {{ $item->maker_fee }}
                            </td>
                            <td>
                                {{ $item->symbol_quote }}
                            </td>
                            <td>
                                <div class='btn-group'>
                                    {{--edit--}}
                                    <a href='{{ route("rabsana-trade.admin-api.v1.commissions.edit" , $item) }}'
                                        class='btn btn-sm btn-success' data-toggle='tooltip'
                                        title="@lang('trade::rabsana.edit')">
                                        <span class='fas fa-edit'></span>
                                    </a>

                                    {{--destroy--}}
                                    <form action="{{ route("rabsana-trade.admin-api.v1.commissions.destroy" , $item) }}"
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
                {{ $commissions->onEachSide(7)->links() }}
            </div>
        </div>
    </div>
</div>
@endsection


{{-- write custom javascript --}}
@push(config('rabsana-trade.views.admin.scripts-stack'))
@endpush
