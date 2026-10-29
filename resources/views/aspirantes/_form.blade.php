<div class="mb-3">
    <label for="nombre_completo" class="form-label">Nombre completo</label>
    <input type="text" id="nombre_completo" name="nombre_completo" maxlength="150" required
           value="{{ old('nombre_completo', $aspirante?->nombre_completo) }}"
           class="form-control @error('nombre_completo') is-invalid @enderror">
    @error('nombre_completo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="puesto" class="form-label">Puesto al que aplica</label>
    <input type="text" id="puesto" name="puesto" maxlength="150" required
           value="{{ old('puesto', $aspirante?->puesto) }}"
           class="form-control @error('puesto') is-invalid @enderror">
    @error('puesto')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="estado_civil" class="form-label">Estado civil</label>
        <select id="estado_civil" name="estado_civil" required
                class="form-select @error('estado_civil') is-invalid @enderror">
            <option value="">Selecciona una opción</option>
            @foreach ($estadosCiviles as $estado)
                <option value="{{ $estado->value }}" @selected(old('estado_civil', $aspirante?->estado_civil?->value) === $estado->value)>
                    {{ $estado->etiqueta() }}
                </option>
            @endforeach
        </select>
        @error('estado_civil')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required data-calendario
               max="{{ now()->subDay()->toDateString() }}"
               value="{{ old('fecha_nacimiento', $aspirante?->fecha_nacimiento?->format('Y-m-d')) }}"
               class="form-control @error('fecha_nacimiento') is-invalid @enderror">
        @error('fecha_nacimiento')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-4">
    <label for="carrera" class="form-label">Carrera o formación académica</label>
    <input type="text" id="carrera" name="carrera" maxlength="150" required
           value="{{ old('carrera', $aspirante?->carrera) }}"
           class="form-control @error('carrera') is-invalid @enderror">
    @error('carrera')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@include('partials.calendario')
