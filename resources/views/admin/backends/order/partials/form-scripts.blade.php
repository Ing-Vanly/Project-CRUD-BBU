<script>
    $(function() {
        const productSelect = $('#product_id');
        const quantityInput = $('#quantity');
        const unitPriceText = $('#unitPriceText');
        const stockText = $('#availableStockText');
        const totalAmountText = $('#totalAmountText');
        const orderedAtField = $('#ordered_at');

        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: function() {
                return $(this).data('placeholder');
            },
            allowClear: true
        });

        orderedAtField.daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: false,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD HH:mm',
                cancelLabel: '{{ __('Clear') }}'
            }
        });

        orderedAtField.on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm'));
        });

        orderedAtField.on('cancel.daterangepicker', function() {
            $(this).val('');
        });

        const initialValue = orderedAtField.val();
        if (initialValue) {
            const parsed = moment(initialValue, 'YYYY-MM-DD HH:mm');
            if (parsed.isValid()) {
                orderedAtField.data('daterangepicker').setStartDate(parsed);
                orderedAtField.data('daterangepicker').setEndDate(parsed);
                orderedAtField.val(parsed.format('YYYY-MM-DD HH:mm'));
            }
        }

        function updateSummary() {
            const selectedOption = productSelect.find(':selected');
            const price = parseFloat(selectedOption.data('price')) || 0;
            const stock = parseInt(selectedOption.data('stock')) || 0;
            const quantity = parseInt(quantityInput.val()) || 0;

            unitPriceText.text('$' + price.toFixed(2));
            stockText.text(stock);
            totalAmountText.text('$' + (price * quantity).toFixed(2));
        }

        productSelect.on('change', updateSummary);
        quantityInput.on('input', updateSummary);

        updateSummary();
    });
</script>
