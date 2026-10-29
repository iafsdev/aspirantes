@extends('layouts.app')

@section('titulo', 'Registrar aspirante')

@section('contenido')
    <div class="card shadow-sm mx-auto" style="max-width: 720px">
        <div class="card-body">
            <h1 class="h4 mb-4">Registrar aspirante</h1>

            <form method="POST" action="{{ route('aspirantes.store') }}">
                @csrf
                @include('aspirantes._form', ['aspirante' => null])

                <button type="submit" class="btn btn-primary">Guardar aspirante</button>
                <a href="{{ route('aspirantes.index') }}" class="btn btn-link">Cancelar</a>
            </form>
        </div>
    </div>
@endsection
