<?php

namespace App\Http\Controllers;

use App\Enums\EstadoCivil;
use App\Http\Requests\StoreAspiranteRequest;
use App\Models\Aspirante;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AspiranteController extends Controller
{
    public function create(): View
    {
        return view('aspirantes.create', [
            'estadosCiviles' => EstadoCivil::cases(),
        ]);
    }

    public function store(StoreAspiranteRequest $request): RedirectResponse
    {
        Aspirante::create($request->validated());

        return redirect()
            ->route('aspirantes.create')
            ->with('exito', 'Aspirante registrado correctamente.');
    }
}
