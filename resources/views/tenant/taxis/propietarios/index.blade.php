@extends('tenant.layouts.app')

@section('content')
    <tenant-taxis-propietarios-index
        :configuration="{{ \App\Models\Tenant\Configuration::getPublicConfig() }}"></tenant-taxis-propietarios-index>
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
