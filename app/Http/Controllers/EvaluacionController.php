<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvaluacionRequest;
use App\Models\Aspirante;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Cada aspirante tiene a lo más una evaluación: si ya existe se edita, si no se crea.
 */
class EvaluacionController extends Controller
{
    public function create(Aspirante $aspirante): View|RedirectResponse
    {
        if ($aspirante->evaluacion()->exists()) {
            return redirect()->route('aspirantes.evaluacion.edit', $aspirante);
        }

        return view('evaluaciones.create', ['aspirante' => $aspirante]);
    }

    public function store(StoreEvaluacionRequest $request, Aspirante $aspirante): RedirectResponse
    {
        if ($aspirante->evaluacion()->exists()) {
            return redirect()
                ->route('aspirantes.evaluacion.edit', $aspirante)
                ->with('aviso', 'Este aspirante ya tiene una evaluación; puedes editarla aquí.');
        }

        $aspirante->evaluacion()->create($request->validated());

        return redirect()
            ->route('aspirantes.evaluacion.edit', $aspirante)
            ->with('exito', 'Evaluación registrada correctamente.');
    }

    public function edit(Aspirante $aspirante): View|RedirectResponse
    {
        if (! $aspirante->evaluacion) {
            return redirect()->route('aspirantes.evaluacion.create', $aspirante);
        }

        return view('evaluaciones.edit', [
            'aspirante' => $aspirante,
            'evaluacion' => $aspirante->evaluacion,
        ]);
    }

    public function update(StoreEvaluacionRequest $request, Aspirante $aspirante): RedirectResponse
    {
        $aspirante->evaluacion()->firstOrFail()->update($request->validated());

        return redirect()
            ->route('aspirantes.evaluacion.edit', $aspirante)
            ->with('exito', 'Evaluación actualizada correctamente.');
    }
}
