@once
    @push('js')
        <script>
            window.attachImagePreview = function(inputId, previewId, placeholderHtml = '') {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);

                if (!input || !preview) {
                    return;
                }

                input.addEventListener('change', function(event) {
                    const file = event.target.files[0];

                    if (!file) {
                        if (placeholderHtml) {
                            preview.innerHTML = placeholderHtml;
                        }
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" class="img-fluid" alt="preview">`;
                    };
                    reader.readAsDataURL(file);
                });
            };
        </script>
    @endpush
@endonce
