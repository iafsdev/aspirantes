@extends('layouts.app')

@section('titulo', 'Evaluar aspirante')

@section('contenido')
    <div class="card shadow-sm mx-auto" style="max-width: 720px">
        <div class="card-body">
            <h1 class="h4 mb-1">Evaluar aspirante</h1>
            <p class="text-muted mb-4">{{ $aspirante->nombre_completo }} · {{ $aspirante->puesto }}</p>

            <form method="POST" action="{{ route('aspirantes.evaluacion.store', $aspirante) }}">
                @csrf
                @include('evaluaciones._form', ['evaluacion' => null])

                <button type="submit" class="btn btn-primary">Guardar evaluación</button>
            </form>
        </div>
    </div>
@endsection
