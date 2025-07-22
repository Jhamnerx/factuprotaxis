@extends('tenant.layouts.app')

@section('title', 'Dashboard Propietario')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h2>Bienvenido, {{ $user->name }}</h2>
                    <p class="text-muted">Panel de Control - Propietario</p>
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
                        <p><strong>Documento:</strong> {{ $user->number }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Teléfono 1:</strong> {{ $user->telephone_1 }}</p>
                        @if ($user->telephone_2)
                            <p><strong>Teléfono 2:</strong> {{ $user->telephone_2 }}</p>
                        @endif
                        @if ($user->telephone_3)
                            <p><strong>Teléfono 3:</strong> {{ $user->telephone_3 }}</p>
                        @endif
                        <p><strong>Dirección:</strong> {{ $user->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-primary">Ver mis Vehículos</a>
                            <a href="#" class="btn btn-info">Ver Contratos</a>
                            <a href="#" class="btn btn-warning">Gestionar Conductores</a>
                            <a href="#" class="btn btn-secondary">Ver Reportes</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Resumen</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h4>0</h4>
                                        <p>Vehículos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h4>0</h4>
                                        <p>Conductores</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h4>0</h4>
                                        <p>Contratos Activos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h4>S/ 0.00</h4>
                                        <p>Ingresos del Mes</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
