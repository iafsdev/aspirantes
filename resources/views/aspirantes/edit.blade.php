@extends('layouts.app')

@section('titulo', 'Editar aspirante')

@section('contenido')
    <div class="card shadow-sm mx-auto" style="max-width: 720px">
        <div class="card-body">
            <h1 class="h4 mb-4">Editar datos del aspirante</h1>

            <form method="POST" action="{{ route('aspirantes.update', $aspirante) }}">
                @csrf
                @method('PUT')
                @include('aspirantes._form')

                <button type="submit" class="btn btn-primary">Actualizar datos</button>
                <a href="{{ route('aspirantes.show', $aspirante) }}" class="btn btn-link">Cancelar</a>
            </form>
        </div>
    </div>
@endsection
