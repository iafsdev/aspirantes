<?php

namespace App\Http\Controllers;

use App\Enums\EstadoCivil;
use App\Http\Requests\StoreAspiranteRequest;
use App\Models\Aspirante;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AspiranteController extends Controller
{
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('buscar', ''));

        $aspirantes = Aspirante::with('evaluacion')
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($query) use ($busqueda) {
                    $query->where('nombre_completo', 'like', "%{$busqueda}%")
                        ->orWhere('puesto', 'like', "%{$busqueda}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('aspirantes.index', [
            'aspirantes' => $aspirantes,
            'busqueda' => $busqueda,
        ]);
    }

    public function create(): View
    {
        return view('aspirantes.create', [
            'estadosCiviles' => EstadoCivil::cases(),
        ]);
    }

    public function store(StoreAspiranteRequest $request): RedirectResponse
    {
        $aspirante = Aspirante::create($request->validated());

        return redirect()
            ->route('aspirantes.show', $aspirante)
            ->with('exito', 'Aspirante registrado correctamente.');
    }

    public function show(Aspirante $aspirante): View
    {
        $aspirante->load('evaluacion');

        return view('aspirantes.show', ['aspirante' => $aspirante]);
    }
}
