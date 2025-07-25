@extends('taxis::layouts.app')

@section('title', 'Dashboard - Propietario')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard del Propietario</h4>
                    <div class="page-title-right">
                        <span class="badge badge-info">{{ $propietario['name'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row">
            <div class="col-xl-2 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fs-12 fw-bold">Total Vehículos</span>
                                <h3 class="mb-0">{{ $stats['total_vehiculos'] }}</h3>
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

            <div class="col-xl-2 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fs-12 fw-bold">Vehículos Activos</span>
                                <h3 class="mb-0">{{ $stats['vehiculos_activos'] }}</h3>
                            </div>
                            <div class="align-self-center flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-success mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-success">
                                        <i class="fe-check-circle fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fs-12 fw-bold">Conductores</span>
                                <h3 class="mb-0">{{ $stats['total_conductores'] }}</h3>
                            </div>
                            <div class="align-self-center flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-info mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-info">
                                        <i class="fe-users fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fs-12 fw-bold">Contratos Activos</span>
                                <h3 class="mb-0">{{ $stats['contratos_activos'] }}</h3>
                            </div>
                            <div class="align-self-center flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-warning mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-warning">
                                        <i class="fe-file-text fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fs-12 fw-bold">Solicitudes Pendientes</span>
                                <h3 class="mb-0">{{ $stats['solicitudes_pendientes'] }}</h3>
                            </div>
                            <div class="align-self-center flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-danger mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-danger">
                                        <i class="fe-clock fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fs-12 fw-bold">Pagos Pendientes</span>
                                <h3 class="mb-0">{{ $stats['pagos_pendientes'] }}</h3>
                            </div>
                            <div class="align-self-center flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-secondary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-secondary">
                                        <i class="fe-credit-card fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehículos Recientes -->
        @if ($vehiculos_recientes->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Mis Vehículos Recientes</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Placa</th>
                                            <th>Número Interno</th>
                                            <th>Marca/Modelo</th>
                                            <th>Conductor</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vehiculos_recientes as $vehiculo)
                                            <tr>
                                                <td><strong>{{ $vehiculo->placa }}</strong></td>
                                                <td>{{ $vehiculo->numero_interno }}</td>
                                                <td>{{ $vehiculo->marca->name ?? 'N/A' }} /
                                                    {{ $vehiculo->modelo->name ?? 'N/A' }}</td>
                                                <td>{{ $vehiculo->conductor->name ?? 'Sin asignar' }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $vehiculo->estado == 'activo' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($vehiculo->estado) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('taxis.propietario.vehiculos.show', $vehiculo->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fe-eye"></i> Ver
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('taxis.propietario.vehiculos') }}" class="btn btn-primary">
                                    <i class="fe-list me-1"></i> Ver Todos los Vehículos
                                </a>
                            </div>
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
                            <div class="col-md-2 col-sm-4 mb-3">
                                <a href="{{ route('taxis.propietario.vehiculos') }}"
                                    class="btn btn-outline-primary btn-block">
                                    <i class="fe-truck me-1"></i> Mis Vehículos
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 mb-3">
                                <a href="{{ route('taxis.propietario.conductores') }}"
                                    class="btn btn-outline-info btn-block">
                                    <i class="fe-users me-1"></i> Conductores
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 mb-3">
                                <a href="{{ route('taxis.propietario.contratos') }}"
                                    class="btn btn-outline-warning btn-block">
                                    <i class="fe-file-text me-1"></i> Contratos
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 mb-3">
                                <a href="{{ route('taxis.propietario.solicitudes') }}"
                                    class="btn btn-outline-danger btn-block">
                                    <i class="fe-clock me-1"></i> Solicitudes
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 mb-3">
                                <a href="{{ route('taxis.propietario.pagos') }}"
                                    class="btn btn-outline-success btn-block">
                                    <i class="fe-credit-card me-1"></i> Pagos
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 mb-3">
                                <a href="{{ route('taxis.propietario.constancias') }}"
                                    class="btn btn-outline-secondary btn-block">
                                    <i class="fe-award me-1"></i> Constancias
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
