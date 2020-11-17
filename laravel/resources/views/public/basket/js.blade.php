<script>
    function re_count(obj) {
        var id_body = $(obj).attr('data-id');
        $.ajax({
            url: '{{ route('basket.re_count') }}',
            type: 'post',
            data: {
                new_amount: $(obj).val(),
                id_body: id_body
            },
            success: function (result) {
                $('#sum' + id_body).text(jQuery(result).attr('sum_item'));
                $('#sum_all').text(jQuery(result).attr('sum_all'));
//                    $('#itog').text(jQuery(result).attr('itog'));
//                    $('#discount').text(jQuery(result).attr('discount'));
            }
        })
    }
    $('input.spin').each(function () {
        $(this).TouchSpin({
            min: 1,
            max: 99
        });
        $(this)
                .on('change', function () {
                    re_count(this);
                })
                .on('keyup', function () {
                    re_count(this);
                });
    });
</script>