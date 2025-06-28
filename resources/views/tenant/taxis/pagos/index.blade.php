@extends('tenant.layouts.app')

@section('content')

    <tenant-taxis-pagos-form :type-user="{{ json_encode(Auth::user()->type) }}"
        :auth-user="{{ json_encode(Auth::user()->getDataOnlyAuthUser()) }}" :id-user="{{ json_encode(Auth::user()->id) }}"
        :company="{{ $company }}"
        :configuration="{{ \App\Models\Tenant\Configuration::getPublicConfig() }}"></tenant-taxis-pagos-form>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            'use strict';
            $(".tableScrollTop,.tableWide-wrapper").scroll(function() {
                $(".tableWide-wrapper,.tableScrollTop")
                    .scrollLeft($(this).scrollLeft());
            });
        });
    </script>
@endpush
@section('js')

@stop
