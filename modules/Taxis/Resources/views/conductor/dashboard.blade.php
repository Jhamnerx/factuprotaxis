@extends('taxis::layouts.master')

@section('title', 'Dashboard - Conductor')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard del Conductor</h4>
                    <div class="page-title-right">
                        <span class="badge badge-info">{{ $conductor['name'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fs-12 fw-bold">Vehículo Asignado</span>
                                <h3 class="mb-0">{{ $stats['vehiculo_asignado'] ? 'Sí' : 'No' }}</h3>
                            </div>
                            <div class="align-self-center flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="fe-truck fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fs-12 fw-bold">Permisos Vigentes</span>
                                <h3 class="mb-0">{{ $stats['permisos_vigentes'] }}</h3>
                            </div>
                            <div class="align-self-center flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-success mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-success">
                                        <i class="fe-file-text fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fs-12 fw-bold">Pagos Pendientes</span>
                                <h3 class="mb-0">{{ $stats['pagos_pendientes'] }}</h3>
                            </div>
                            <div class="align-self-center flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-warning mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-warning">
                                        <i class="fe-credit-card fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fs-12 fw-bold">Servicios Programados</span>
                                <h3 class="mb-0">{{ $stats['servicios_programados'] }}</h3>
                            </div>
                            <div class="align-self-center flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-info mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-info">
                                        <i class="fe-calendar fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Vehículo Asignado -->
        @if ($vehiculo)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Mi Vehículo Asignado</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td><strong>Placa:</strong></td>
                                            <td>{{ $vehiculo->placa }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Número Interno:</strong></td>
                                            <td>{{ $vehiculo->numero_interno }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Marca:</strong></td>
                                            <td>{{ $vehiculo->marca->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Modelo:</strong></td>
                                            <td>{{ $vehiculo->modelo->name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td><strong>Año:</strong></td>
                                            <td>{{ $vehiculo->anio }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Color:</strong></td>
                                            <td>{{ $vehiculo->color }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Estado:</strong></td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $vehiculo->estado == 'activo' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($vehiculo->estado) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Propietario:</strong></td>
                                            <td>{{ $vehiculo->propietario->name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('taxis.conductor.vehiculo') }}" class="btn btn-primary">
                                    <i class="fe-eye me-1"></i> Ver Detalles Completos
                                </a>
                            </div>
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
                            <p class="text-muted">Contacta con el administrador para que te asigne un vehículo.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Accesos Rápidos -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Accesos Rápidos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('taxis.conductor.permisos') }}"
                                    class="btn btn-outline-primary btn-block">
                                    <i class="fe-file-text me-1"></i> Mis Permisos
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('taxis.conductor.pagos') }}" class="btn btn-outline-success btn-block">
                                    <i class="fe-credit-card me-1"></i> Mis Pagos
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('taxis.conductor.servicios') }}" class="btn btn-outline-info btn-block">
                                    <i class="fe-calendar me-1"></i> Mis Servicios
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('taxis.conductor.perfil') }}"
                                    class="btn btn-outline-secondary btn-block">
                                    <i class="fe-user me-1"></i> Mi Perfil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
