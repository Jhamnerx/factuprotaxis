@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <tenant-taxis-mensajes-plantillas></tenant-taxis-mensajes-plantillas>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Scripts adicionales si son necesarios
    </script>
@endpush
