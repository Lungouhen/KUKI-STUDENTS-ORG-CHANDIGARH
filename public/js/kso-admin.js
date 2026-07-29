/*!
 * KSO Chandigarh — admin CMS behaviour (jQuery)
 * ---------------------------------------------------------------------------
 * Progressive enhancements for the content-management screens. Everything here
 * is additive: with JavaScript disabled the forms still submit and the tables
 * still render, they just lose the conveniences.
 *
 *   [data-image-preview]   live thumbnail preview before upload
 *   [data-filter-input]    client-side filtering of a card/table list
 *   [data-confirm]         confirm before submitting a destructive form
 *   [data-slug-source]     auto-fill a slug field from a title field
 *   [data-char-count]      live character counter for meta/description fields
 */
(function ($) {
    'use strict';

    if (!$) {
        console.warn('[kso-admin] jQuery is required but was not loaded.');
        return;
    }

    $(function () {

        /* ─── Live image preview on file inputs ───────────────────────── */
        // <input type="file" data-image-preview="#targetImg">
        $(document).on('change', '[data-image-preview]', function () {
            var target = $($(this).data('image-preview'));
            var file = this.files && this.files[0];
            if (!target.length || !file) return;

            if (!/^image\//.test(file.type)) {
                target.attr('src', '').hide();
                return;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                target.attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        });

        /* ─── Client-side list filtering ──────────────────────────────── */
        // <input data-filter-input data-filter-target=".js-item">
        $('[data-filter-input]').on('input', function () {
            var term = $.trim($(this).val()).toLowerCase();
            var selector = $(this).data('filter-target') || '.js-filter-item';
            var $items = $(selector);
            var visible = 0;

            $items.each(function () {
                var haystack = ($(this).data('filter-text') || $(this).text() || '').toString().toLowerCase();
                var match = !term || haystack.indexOf(term) !== -1;
                $(this).toggle(match);
                if (match) visible++;
            });

            $('[data-filter-empty]').toggle(visible === 0);
            $('[data-filter-count]').text(visible);
        });

        /* ─── Confirm destructive submits ─────────────────────────────── */
        // <form data-confirm="Delete this photo?">
        $(document).on('submit', 'form[data-confirm]', function (e) {
            var message = $(this).data('confirm') || 'Are you sure?';
            var form = this;

            // Prefer SweetAlert2 (already bundled) over the native dialog.
            if (typeof window.Swal !== 'undefined') {
                if ($(form).data('confirmed')) return true;
                e.preventDefault();
                window.Swal.fire({
                    title: 'Please confirm',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, continue'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $(form).data('confirmed', true);
                        form.submit();
                    }
                });
                return false;
            }

            return window.confirm(message);
        });

        /* ─── Auto-slug ───────────────────────────────────────────────── */
        // <input data-slug-source="#title" name="slug">
        $('[data-slug-source]').each(function () {
            var $slug = $(this);
            var $source = $($slug.data('slug-source'));
            if (!$source.length) return;

            $source.on('input', function () {
                // Stop syncing once an editor types their own slug.
                if ($slug.data('touched')) return;
                var value = $(this).val()
                    .toString()
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                $slug.val(value);
            });

            $slug.on('input', function () { $slug.data('touched', true); });
        });

        /* ─── Character counters ──────────────────────────────────────── */
        // <textarea data-char-count="160"> renders "n / 160" beneath itself
        $('[data-char-count]').each(function () {
            var $field = $(this);
            var max = parseInt($field.data('char-count'), 10) || 0;
            var $out = $('<div class="form-text extra-small text-end"></div>');
            $field.after($out);

            var render = function () {
                var len = ($field.val() || '').length;
                $out.text(max ? len + ' / ' + max : len + ' characters')
                    .toggleClass('text-danger', max > 0 && len > max);
            };

            $field.on('input', render);
            render();
        });
    });

})(window.jQuery);
