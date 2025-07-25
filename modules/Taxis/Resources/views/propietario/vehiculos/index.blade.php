@extends('taxis::layouts.master')

@section('title', 'Mis Vehículos - Propietario')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Mis Vehículos</h4>
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
                        <h5 class="card-title mb-0">Lista de Vehículos</h5>
                    </div>
                    <div class="card-body">
                        <tenant-taxis-propietario-vehiculos :propietario-id="{{ $propietario['id'] }}"
                            records-url="{{ route('taxis.propietario.vehiculos.records') }}">
                        </tenant-taxis-propietario-vehiculos>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
