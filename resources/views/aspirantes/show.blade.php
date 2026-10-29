@extends('layouts.app')

@section('titulo', $aspirante->nombre_completo)

@section('contenido')
    <div class="mb-3">
        <a href="{{ route('aspirantes.index') }}">&larr; Volver al listado</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-4">
                        <h1 class="h4 mb-0">{{ $aspirante->nombre_completo }}</h1>
                        <a href="{{ route('aspirantes.edit', $aspirante) }}" class="btn btn-sm btn-outline-primary text-nowrap">Editar datos</a>
                    </div>

                    <dl class="mb-0">
                        <dt>Puesto al que aplica</dt>
                        <dd>{{ $aspirante->puesto }}</dd>

                        <dt>Estado civil</dt>
                        <dd>{{ $aspirante->estado_civil->etiqueta() }}</dd>

                        <dt>Fecha de nacimiento</dt>
                        <dd>{{ $aspirante->fecha_nacimiento->format('d/m/Y') }}</dd>

                        <dt>Carrera o formación académica</dt>
                        <dd class="mb-0">{{ $aspirante->carrera }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h5 mb-0">Evaluación</h2>
                        @if ($aspirante->evaluacion)
                            <a href="{{ route('aspirantes.evaluacion.edit', $aspirante) }}" class="btn btn-sm btn-outline-primary">Editar evaluación</a>
                        @endif
                    </div>

                    @if ($evaluacion = $aspirante->evaluacion)
                        <table class="table mb-4">
                            <tbody>
                                <tr>
                                    <th scope="row" class="fw-normal">Conocimientos técnicos</th>
                                    <td class="text-end">{{ number_format($evaluacion->conocimientos_tecnicos, 2) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-normal">Referencias</th>
                                    <td class="text-end">{{ number_format($evaluacion->referencias, 2) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-normal">Habilidades blandas</th>
                                    <td class="text-end">{{ number_format($evaluacion->habilidades_blandas, 2) }}</td>
                                </tr>
                                <tr class="table-light">
                                    <th scope="row">Promedio</th>
                                    <td class="text-end fw-bold">{{ number_format($evaluacion->promedio, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <h3 class="h6">Comentarios generales</h3>
                        @if ($evaluacion->comentarios)
                            <p class="mb-0 text-break" style="white-space: pre-line">{{ $evaluacion->comentarios }}</p>
                        @else
                            <p class="mb-0 text-muted">Sin comentarios.</p>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Este aspirante aún no ha sido evaluado.</p>
                            <a href="{{ route('aspirantes.evaluacion.create', $aspirante) }}" class="btn btn-primary">Evaluar aspirante</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
