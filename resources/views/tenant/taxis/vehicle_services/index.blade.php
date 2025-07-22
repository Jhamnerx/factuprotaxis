@extends('tenant.layouts.app')

@section('content')
    <tenant-taxis-services-index
        :configuration="{{ \App\Models\Tenant\Configuration::getPublicConfig() }}"></tenant-taxis-services-index>
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
