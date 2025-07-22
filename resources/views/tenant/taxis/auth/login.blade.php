@extends('tenant.layouts.app')

@section('title', 'Acceso al Sistema de Taxis')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Acceso al Sistema de Taxis</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('taxis.login') }}">
                            @csrf

                            <!-- Tipo de Usuario -->
                            <div class="form-group mb-3">
                                <label class="form-label">Tipo de Usuario</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" id="propietario"
                                        value="propietario" {{ old('user_type') == 'propietario' ? 'checked' : '' }}
                                        required>
                                    <label class="form-check-label" for="propietario">
                                        Propietario
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" id="conductor"
                                        value="conductor" {{ old('user_type') == 'conductor' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="conductor">
                                        Conductor
                                    </label>
                                </div>
                                @error('user_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Recordarme
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary w-100">
                                    Iniciar Sesión
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
