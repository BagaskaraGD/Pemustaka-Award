$(document).ready(function() {
    $('#kodebuku').select2({
        placeholder: 'Cari Kode Buku...',
        ajax: {
            url: routeBukuSearch, // Route menuju controller Laravel
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.induk,
                            text: item.induk + ' - ' + item.judul,
                            judul: item.judul,
                            pengarang: [item.pengarang1, item.pengarang2, item.pengarang3]
                        };
                    })
                };
            },
            cache: true
        }
    });

    $('#kodebuku').on('select2:select', function(e) {
        var data = e.params.data;
        $('#judul').val(data.judul); 
        $('#pengarang').val(data.pengarang.join(', ')); // <- ini dibetulkan
    });
});