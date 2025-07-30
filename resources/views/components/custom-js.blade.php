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

        jQuery('.js-maxlength:not(.js-maxlength-enabled)').each((index, element) => {
            let el = jQuery(element);

            // Add .js-maxlength-enabled class to tag it as activated and init it
            el.addClass('js-maxlength-enabled').maxlength({
                alwaysShow: el.data('always-show') ? true : false,
                threshold: el.data('threshold') || 10,
                warningClass: el.data('warning-class') || 'badge bg-warning',
                limitReachedClass: el.data('limit-reached-class') || 'badge bg-danger',
                placement: el.data('placement') || 'bottom',
                preText: el.data('pre-text') || '',
                separator: el.data('separator') || '/',
                postText: el.data('post-text') || '',
                validate: true
            });
        });

        $val = $('.sort-input').val();
        if ($val) {
            $isAsc = $val === "asc"
            $isDesc = $val === "desc"
            $name = $('.sort-input').data('name')

            $th = $(`.sortable[data-name=${$name}]`);

            if ($isAsc) {
                $th.addClass('--asc');
            } else if ($isDesc) {
                $th.addClass('--desc');
            }
        }

        $('.sortable').on('click', function () {
            $name = $(this).data('name')
            if (!$name) return;
            $isAsc = $(this).hasClass('--asc')
            $isDesc = $(this).hasClass('--desc')
            $search = $('.search-form');
            $sortInput = $('.sort-input');

            if ($isAsc) {
                $(this).removeClass('--asc').addClass('--desc');
                $sortInput.attr('name', `sort[${$name}]`).val('desc')
            } else if ($isDesc) {
                $(this).removeClass('--desc');
                $sortInput.attr('name', ``).val('')
            } else {
                $(this).addClass('--asc');
                $sortInput.attr('name', `sort[${$name}]`).val('asc')
            }
            $search.submit();
        })

        $('form').on('submit', function () {
            $(this).find('[type=submit]').prop('disabled', true);
        });
    });
</script>
