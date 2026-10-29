@extends('layouts.app')

@section('titulo', 'Editar evaluación')

@section('contenido')
    <div class="card shadow-sm mx-auto" style="max-width: 720px">
        <div class="card-body">
            <h1 class="h4 mb-1">Editar evaluación</h1>
            <p class="text-muted mb-4">{{ $aspirante->nombre_completo }} · {{ $aspirante->puesto }}</p>

            <form method="POST" action="{{ route('aspirantes.evaluacion.update', $aspirante) }}">
                @csrf
                @method('PUT')
                @include('evaluaciones._form')

                <button type="submit" class="btn btn-primary">Actualizar evaluación</button>
            </form>
        </div>
    </div>
@endsection
