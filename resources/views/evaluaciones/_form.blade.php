@php
    $criterios = [
        'conocimientos_tecnicos' => 'Conocimientos técnicos',
        'referencias' => 'Referencias',
        'habilidades_blandas' => 'Habilidades blandas',
    ];
@endphp

<div class="row">
    @foreach ($criterios as $campo => $etiqueta)
        <div class="col-md-4 mb-3">
            <label for="{{ $campo }}" class="form-label">{{ $etiqueta }}</label>
            <input type="number" id="{{ $campo }}" name="{{ $campo }}"
                   min="0" max="10" step="0.01" required
                   value="{{ old($campo, $evaluacion?->$campo) }}"
                   class="form-control @error($campo) is-invalid @enderror">
            <div class="form-text">De 0 a 10, hasta 2 decimales.</div>
            @error($campo)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endforeach
</div>

<div class="mb-4">
    <label for="comentarios" class="form-label">Comentarios generales <span class="text-muted">(opcional)</span></label>
    <textarea id="comentarios" name="comentarios" rows="5" maxlength="5000"
              class="form-control @error('comentarios') is-invalid @enderror">{{ old('comentarios', $evaluacion?->comentarios) }}</textarea>
    @error('comentarios')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
