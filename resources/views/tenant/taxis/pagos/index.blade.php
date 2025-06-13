@extends('layouts.tenant.admin')


@section('headerVariant', 'v2')
@section('sidebarVariant', 'v2')


@section('contenido')

    {{-- @livewire('tenant.admin.taxis.pagos.index') --}}
    @livewire('tenant.admin.taxis.pagos.create')


@stop

@push('modals')
    @livewire('tenant.admin.taxis.pagos.create-subscription-invoice')
    @livewire('tenant.admin.taxis.unidades.create')
@endpush


{{-- section de js --}}
@section('js')

@stop
