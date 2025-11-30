@once
    @push('css')
        <style>
            .media-preview {
                border: 2px dashed #dee2e6;
                border-radius: 0.5rem;
                min-height: 160px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f8f9fa;
                padding: 1rem;
            }

            .media-preview img {
                max-height: 140px;
                max-width: 100%;
                object-fit: contain;
            }
        </style>
    @endpush
@endonce
