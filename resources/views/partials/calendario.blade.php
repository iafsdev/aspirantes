{{-- Muestra las fechas como dd/mm/aaaa; el valor enviado al servidor sigue siendo aaaa-mm-dd. --}}
@push('estilos')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/es.js"></script>
    <script>
        document.querySelectorAll('[data-calendario]').forEach((campo) => {
            flatpickr(campo, {
                locale: 'es',
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd/m/Y',
                maxDate: campo.max || null,
            });
        });
    </script>
@endpush
