$(document).ready(function() {
    $('#rekomendasi').select2({
        placeholder: 'Cari Dosen/Tendik...',
        ajax: {
            url: routeRekomendasiSearch, // Route menuju controller Laravel
            dataType: 'json',
            delay: 250, // Delay request untuk optimasi
            data: function(params) {
                return {
                    q: params.term // Kirim input pencarian ke server
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.nik,
                            text: item.nik + ' - ' + item
                                .nama, // Format tampilan
                        };
                    })
                };
            },
            cache: true
        }
    });

});