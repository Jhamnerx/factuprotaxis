@extends('taxis::layouts.master')

@section('title', 'Mis Contratos - Propietario')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Mis Contratos</h4>
                    <div class="page-title-right">
                        <a href="{{ route('taxis.propietario.dashboard') }}" class="btn btn-secondary">
                            <i class="fe-arrow-left me-1"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lista de Contratos</h5>
                    </div>
                    <div class="card-body">
                        <tenant-taxis-propietario-contratos :propietario-id="{{ $propietario['id'] }}"
                            records-url="{{ route('taxis.propietario.contratos.records') }}">
                        </tenant-taxis-propietario-contratos>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
