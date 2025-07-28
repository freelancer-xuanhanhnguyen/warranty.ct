<script>
    $(document).ready(function () {
        $('.invalid-feedback').each(function () {
            const $feedback = $(this);
            const $fieldWrapper = $feedback.closest('.mb-4'); // hoặc div bao quanh field
            $fieldWrapper.find('input, select, textarea').addClass('is-invalid');
        });

        $('.search-form select').on('change', function () {
            $('.search-form').submit();
        })
    });
</script>
