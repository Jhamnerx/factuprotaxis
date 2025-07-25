@extends('taxis::layouts.master')

@section('title', 'Mis Permisos - Conductor')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Mis Permisos</h4>
                    <div class="page-title-right">
                        <a href="{{ route('taxis.conductor.dashboard') }}" class="btn btn-secondary">
                            <i class="fe-arrow-left me-1"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if ($vehiculo)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Permisos del Vehículo: {{ $vehiculo->placa }}</h5>
                        </div>
                        <div class="card-body">
                            <tenant-taxis-conductor-permisos :vehiculo-id="{{ $vehiculo->id }}"
                                records-url="{{ route('taxis.conductor.permisos.records') }}">
                            </tenant-taxis-conductor-permisos>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="my-3">
                                <i class="fe-alert-triangle text-warning" style="font-size: 48px;"></i>
                            </div>
                            <h4>No tienes un vehículo asignado</h4>
                            <p class="text-muted">No puedes ver permisos sin un vehículo asignado.</p>
                            <a href="{{ route('taxis.conductor.dashboard') }}" class="btn btn-primary">
                                Volver al Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
