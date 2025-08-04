@extends('taxis::layouts.master')

@section('title', 'Mi Perfil')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mi Perfil</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Edita tu información personal</p>
                </div>
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('taxis.propietario.dashboard') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                Dashboard
                            </a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Mi
                                    Perfil</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div
                class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 mt-0.5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <div>
                        <h3 class="font-medium mb-2">Errores de validación:</h3>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Summary Card -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="text-center">
                            <!-- Profile Image -->
                            <div class="mb-4">
                                <img class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-gray-200 dark:border-gray-600"
                                    src="{{ asset('theme/dist/img/user4-128x128.jpg') }}" alt="Foto de perfil">
                            </div>

                            <!-- Profile Info -->
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                {{ $propietarioModel->name }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                {{ $propietarioModel->identity_document_type->description ?? 'N/A' }}:
                                {{ $propietarioModel->number }}
                            </p>

                            <!-- Stats -->
                            <div class="grid grid-cols-1 gap-3">
                                <div
                                    class="flex justify-between items-center py-2 px-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Vehículos</span>
                                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                        {{ $propietarioModel->vehiculos()->count() }}
                                    </span>
                                </div>
                                <div
                                    class="flex justify-between items-center py-2 px-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Contratos</span>
                                    <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                        {{ $propietarioModel->contratos()->count() }}
                                    </span>
                                </div>
                                <div
                                    class="flex justify-between items-center py-2 px-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Constancias</span>
                                    <span class="text-sm font-bold text-purple-600 dark:text-purple-400">
                                        {{ \App\Models\Tenant\Taxis\ConstanciaBaja::whereIn('vehiculo_id', $propietarioModel->vehiculos()->pluck('id'))->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Edit Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Editar Información Personal
                        </h3>
                    </div>

                    <form action="{{ route('taxis.propietario.perfil.update') }}" method="POST" class="p-6">
                        @csrf

                        <!-- Información Básica -->
                        <div class="mb-8">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Información Básica
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="identity_document_type_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tipo de Documento <span class="text-red-500">*</span>
                                    </label>
                                    <select name="identity_document_type_id" id="identity_document_type_id" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('identity_document_type_id') border-red-500 @enderror">
                                        <option value="">Seleccionar...</option>
                                        @foreach ($identity_document_types as $type)
                                            <option value="{{ $type->id }}"
                                                {{ old('identity_document_type_id', $propietarioModel->identity_document_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('identity_document_type_id'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('identity_document_type_id') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label for="number"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Número de Documento <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="number" id="number" required
                                        value="{{ old('number', $propietarioModel->number) }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('number') border-red-500 @enderror">
                                    @if ($errors->has('number'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('number') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nombres y Apellidos <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                        value="{{ old('name', $propietarioModel->name) }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror">
                                    @if ($errors->has('name'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('name') }}
                                        </p>
                                    @endif
                                </div>

                                <div>
                                    <label for="fecha_nacimiento"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Fecha de Nacimiento
                                    </label>
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                                        value="{{ old('fecha_nacimiento', $propietarioModel->fecha_nacimiento ? $propietarioModel->fecha_nacimiento->format('Y-m-d') : '') }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_nacimiento') border-red-500 @enderror">
                                    @if ($errors->has('fecha_nacimiento'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('fecha_nacimiento') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Correo Electrónico
                                    </label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', $propietarioModel->email) }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror">
                                    @if ($errors->has('email'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('email') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="mb-8">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                                Información de Contacto
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="telephone_1"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Teléfono Principal
                                    </label>
                                    <input type="text" name="telephone_1" id="telephone_1"
                                        value="{{ old('telephone_1', $propietarioModel->telephone_1) }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('telephone_1') border-red-500 @enderror">
                                    @if ($errors->has('telephone_1'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('telephone_1') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label for="telephone_2"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Teléfono Secundario
                                    </label>
                                    <input type="text" name="telephone_2" id="telephone_2"
                                        value="{{ old('telephone_2', $propietarioModel->telephone_2) }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('telephone_2') border-red-500 @enderror">
                                    @if ($errors->has('telephone_2'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('telephone_2') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label for="telephone_3"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Teléfono Adicional
                                    </label>
                                    <input type="text" name="telephone_3" id="telephone_3"
                                        value="{{ old('telephone_3', $propietarioModel->telephone_3) }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('telephone_3') border-red-500 @enderror">
                                    @if ($errors->has('telephone_3'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('telephone_3') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div class="mb-8">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Ubicación
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <label for="country_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        País <span class="text-red-500">*</span>
                                    </label>
                                    <select name="country_id" id="country_id" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('country_id') border-red-500 @enderror">
                                        <option value="">Seleccionar...</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('country_id', $propietarioModel->country_id) == $country->id ? 'selected' : '' }}>
                                                {{ $country->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('country_id'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('country_id') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label for="department_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Departamento <span class="text-red-500">*</span>
                                    </label>
                                    <select name="department_id" id="department_id" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('department_id') border-red-500 @enderror">
                                        <option value="">Seleccionar...</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id', $propietarioModel->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('department_id'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('department_id') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label for="province_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Provincia <span class="text-red-500">*</span>
                                    </label>
                                    <select name="province_id" id="province_id" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('province_id') border-red-500 @enderror">
                                        <option value="">Seleccionar...</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}"
                                                {{ old('province_id', $propietarioModel->province_id) == $province->id ? 'selected' : '' }}>
                                                {{ $province->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('province_id'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('province_id') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label for="district_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Distrito <span class="text-red-500">*</span>
                                    </label>
                                    <select name="district_id" id="district_id" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('district_id') border-red-500 @enderror">
                                        <option value="">Seleccionar...</option>
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}"
                                                {{ old('district_id', $propietarioModel->district_id) == $district->id ? 'selected' : '' }}>
                                                {{ $district->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('district_id'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('district_id') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label for="address"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Dirección
                                </label>
                                <textarea name="address" id="address" rows="2" placeholder="Ingrese la dirección completa"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('address') border-red-500 @enderror">{{ old('address', $propietarioModel->address) }}</textarea>
                                @if ($errors->has('address'))
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('address') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Cambio de Contraseña -->
                        <div class="mb-8 border-t border-gray-200 dark:border-gray-700 pt-8">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Cambiar Contraseña (Opcional)
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Deje estos campos vacíos si no desea
                                cambiar su contraseña.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nueva Contraseña
                                    </label>
                                    <input type="password" name="password" id="password" minlength="6"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror">
                                    @if ($errors->has('password'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ $errors->first('password') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Confirmar Nueva Contraseña
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        minlength="6"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <div id="password-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden">
                                        Las contraseñas no coinciden
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-lg font-medium text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Actualizar Perfil
                            </button>
                            <a href="{{ route('taxis.propietario.dashboard') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 border border-transparent rounded-lg font-medium text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para dropdowns en cascada y validación -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cascading dropdowns para ubicación
            const departmentSelect = document.getElementById('department_id');
            const provinceSelect = document.getElementById('province_id');
            const districtSelect = document.getElementById('district_id');

            departmentSelect?.addEventListener('change', function() {
                const departmentId = this.value;

                // Limpiar selects dependientes
                provinceSelect.innerHTML = '<option value="">Seleccionar...</option>';
                districtSelect.innerHTML = '<option value="">Seleccionar...</option>';

                if (departmentId) {
                    fetch(`/api/provinces/${departmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(province => {
                                const option = document.createElement('option');
                                option.value = province.id;
                                option.textContent = province.description;
                                provinceSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            provinceSelect?.addEventListener('change', function() {
                const provinceId = this.value;

                // Limpiar select dependiente
                districtSelect.innerHTML = '<option value="">Seleccionar...</option>';

                if (provinceId) {
                    fetch(`/api/districts/${provinceId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(district => {
                                const option = document.createElement('option');
                                option.value = district.id;
                                option.textContent = district.description;
                                districtSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            // Validación de contraseña
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('password_confirmation');
            const passwordError = document.getElementById('password-error');

            function validatePasswords() {
                const password = passwordField?.value || '';
                const confirmation = confirmPasswordField?.value || '';

                if (password && confirmation && password !== confirmation) {
                    confirmPasswordField.classList.add('border-red-500');
                    passwordError.classList.remove('hidden');
                } else {
                    confirmPasswordField.classList.remove('border-red-500');
                    passwordError.classList.add('hidden');
                }
            }

            passwordField?.addEventListener('input', validatePasswords);
            confirmPasswordField?.addEventListener('input', validatePasswords);
        });
    </script>
@endsection
