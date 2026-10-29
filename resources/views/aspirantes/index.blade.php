@extends('layouts.app')

@section('titulo', 'Aspirantes')

@section('contenido')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h4 mb-0">Aspirantes registrados</h1>
        <a href="{{ route('aspirantes.create') }}" class="btn btn-primary">Registrar aspirante</a>
    </div>

    <form method="GET" action="{{ route('aspirantes.index') }}" class="mb-3" role="search">
        <div class="input-group">
            <input type="search" name="buscar" value="{{ $busqueda }}" maxlength="150"
                   class="form-control" placeholder="Buscar por nombre o puesto" aria-label="Buscar por nombre o puesto">
            <button type="submit" class="btn btn-outline-primary">Buscar</button>
            @if ($busqueda !== '')
                <a href="{{ route('aspirantes.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        @if ($aspirantes->isEmpty())
            <div class="card-body text-center text-muted py-5">
                @if ($busqueda !== '')
                    No se encontraron aspirantes para "{{ $busqueda }}".
                @else
                    Aún no hay aspirantes registrados.
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre completo</th>
                            <th>Puesto</th>
                            <th class="d-none d-md-table-cell">Carrera</th>
                            <th class="text-center">Promedio</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aspirantes as $aspirante)
                            <tr>
                                <td>{{ $aspirante->nombre_completo }}</td>
                                <td>{{ $aspirante->puesto }}</td>
                                <td class="d-none d-md-table-cell">{{ $aspirante->carrera }}</td>
                                <td class="text-center">
                                    @if ($aspirante->evaluacion)
                                        <span class="badge text-bg-primary">{{ number_format($aspirante->evaluacion->promedio, 2) }}</span>
                                    @else
                                        <span class="badge text-bg-secondary">Sin evaluar</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('aspirantes.show', $aspirante) }}" class="btn btn-sm btn-outline-primary">Ver detalle</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-3">
        {{ $aspirantes->links() }}
    </div>
@endsection
