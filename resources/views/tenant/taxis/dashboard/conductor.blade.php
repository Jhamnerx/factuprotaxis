@extends('tenant.layouts.app')

@section('title', 'Dashboard Conductor')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h2>Bienvenido, {{ $user->name }}</h2>
                    <p class="text-muted">Panel de Control - Conductor</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Información Personal -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Mi Información</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre:</strong> {{ $user->name }}</p>
                        <p><strong>DNI:</strong> {{ $user->number }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Teléfono 1:</strong> {{ $user->telephone_1 }}</p>
                        @if ($user->telephone_2)
                            <p><strong>Teléfono 2:</strong> {{ $user->telephone_2 }}</p>
                        @endif
                        @if ($user->telephone_3)
                            <p><strong>Teléfono 3:</strong> {{ $user->telephone_3 }}</p>
                        @endif
                        <p><strong>Dirección:</strong> {{ $user->address }}</p>
                        @if ($user->fecha_nacimiento)
                            <p><strong>Fecha de Nacimiento:</strong> {{ $user->fecha_nacimiento->format('d/m/Y') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información de Licencia -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Mi Licencia de Conducir</h5>
                    </div>
                    <div class="card-body">
                        @if ($user->licencia)
                            <p><strong>Número:</strong> {{ $user->licencia['numero'] ?? 'No especificado' }}</p>
                            <p><strong>Categoría:</strong> {{ $user->licencia['categoria'] ?? 'No especificada' }}</p>
                            <p><strong>Estado:</strong>
                                <span
                                    class="badge badge-{{ ($user->licencia['estado'] ?? '') == 'VIGENTE' ? 'success' : 'danger' }}">
                                    {{ $user->licencia['estado'] ?? 'No especificado' }}
                                </span>
                            </p>
                            <p><strong>Fecha de Expedición:</strong>
                                {{ $user->licencia['fecha_expedicion'] ?? 'No especificada' }}</p>
                            <p><strong>Fecha de Vencimiento:</strong>
                                {{ $user->licencia['fecha_vencimiento'] ?? 'No especificada' }}</p>
                            @if (isset($user->licencia['restricciones']) && $user->licencia['restricciones'])
                                <p><strong>Restricciones:</strong> {{ $user->licencia['restricciones'] }}</p>
                            @endif
                        @else
                            <p class="text-muted">No hay información de licencia registrada.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-primary">Ver mi Vehículo Asignado</a>
                            <a href="#" class="btn btn-info">Ver Horarios</a>
                            <a href="#" class="btn btn-warning">Reportar Incidencia</a>
                            <a href="#" class="btn btn-secondary">Ver mis Ganancias</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado de Licencia -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Estado de Habilitación</h5>
                    </div>
                    <div class="card-body text-center">
                        @if ($user->hasValidLicense())
                            <div class="alert alert-success">
                                <h4><i class="fas fa-check-circle"></i></h4>
                                <p>Licencia Vigente</p>
                                <small>Habilitado para conducir</small>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <h4><i class="fas fa-exclamation-triangle"></i></h4>
                                <p>Licencia No Vigente</p>
                                <small>Contacte con el administrador</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
