$(document).ready(function() {
    let keranjang = {};

    // Inisialisasi Autocomplete untuk pencarian item
    $('#cari-item').autocomplete({
        source: 'get_item.php',
        select: function(event, ui) {
            tambahItemKeKeranjang(ui.item);
            $(this).val(''); // Kosongkan input setelah memilih
            return false;
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
            .append("<div>" + item.label + "<br><small>Stok: " + item.stok + " | Harga: " + formatRupiah(item.harga) + "</small></div>")
            .appendTo(ul);
    };

    function tambahItemKeKeranjang(item) {
        if (keranjang[item.id]) {
            // Jika item sudah ada, tambah jumlahnya
            keranjang[item.id].jumlah++;
        } else {
            // Jika item baru
            keranjang[item.id] = {
                id: item.id,
                nama: item.value,
                harga: parseFloat(item.harga),
                jumlah: 1,
                stok: parseInt(item.stok)
            };
        }

        if (keranjang[item.id].jumlah > keranjang[item.id].stok) {
            keranjang[item.id].jumlah = keranjang[item.id].stok;
            alert('Stok tidak mencukupi!');
        }
        
        renderKeranjang();
    }

    function renderKeranjang() {
        $('#keranjang-body').empty();
        let totalBelanja = 0;

        if (Object.keys(keranjang).length === 0) {
            $('#keranjang-body').append('<tr><td colspan="5" class="text-center">Keranjang masih kosong</td></tr>');
        } else {
            for (let id in keranjang) {
                let item = keranjang[id];
                let subTotal = item.harga * item.jumlah;
                totalBelanja += subTotal;
                
                let row = `
                    <tr>
                        <td>${item.nama}</td>
                        <td>${formatRupiah(item.harga)}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm qty-input" data-id="${item.id}" value="${item.jumlah}" min="1" max="${item.stok}">
                        </td>
                        <td>${formatRupiah(subTotal)}</td>
                        <td>
                            <button class="btn btn-danger btn-sm hapus-item" data-id="${item.id}"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                `;
                $('#keranjang-body').append(row);
            }
        }

        updateTotal(totalBelanja);
    }
    
    // Event handler untuk ubah kuantitas
    $('#keranjang-body').on('change', '.qty-input', function() {
        let id = $(this).data('id');
        let jumlahBaru = parseInt($(this).val());
        if(jumlahBaru > keranjang[id].stok) {
            jumlahBaru = keranjang[id].stok;
            $(this).val(jumlahBaru);
            alert('Stok tidak mencukupi!');
        }
        keranjang[id].jumlah = jumlahBaru;
        renderKeranjang();
    });

    // Event handler untuk hapus item
    $('#keranjang-body').on('click', '.hapus-item', function() {
        let id = $(this).data('id');
        delete keranjang[id];
        renderKeranjang();
    });

    function updateTotal(totalBelanja) {
        $('#total-belanja-text').text(formatRupiah(totalBelanja));
        
        let pajakPersen = parseFloat($('#pajak-persen').val()) || 0;
        let pajakNominal = totalBelanja * (pajakPersen / 100);
        let totalFinal = totalBelanja + pajakNominal;

        $('#total-final-text').text(formatRupiah(totalFinal));
        $('#total-final-input').val(totalFinal.toFixed(2));
        
        updateKembalian();
    }
    
    $('#pajak-persen, #tunai').on('keyup change', function(){
        let totalBelanja = 0;
         for (let id in keranjang) {
            totalBelanja += keranjang[id].harga * keranjang[id].jumlah;
        }
        updateTotal(totalBelanja);
    });

    function updateKembalian(){
        let totalFinal = parseFloat($('#total-final-input').val()) || 0;
        let tunai = parseFloat($('#tunai').val().replace(/[^0-9]/g, '')) || 0;
        let kembalian = tunai - totalFinal;

        $('#kembalian-text').text(formatRupiah(kembalian > 0 ? kembalian : 0));
    }
    
    // Fungsi submit form transaksi
    $('#form-transaksi').on('submit', function(e){
        if (Object.keys(keranjang).length === 0) {
            alert('Keranjang tidak boleh kosong!');
            e.preventDefault();
            return;
        }

        $('#keranjang-json').val(JSON.stringify(keranjang));
    });


    function formatRupiah(angka) {
        let number_string = angka.toString().replace(/[^,\d]/g, ''),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return 'Rp ' + rupiah;
    }
     
    // untuk meng-embed jQuery UI CSS
    $('head').append('<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">');

});

// jQuery UI diperlukan untuk autocomplete
// Pastikan script jQuery UI sudah di-load setelah jQuery di footer
// <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> (tambahkan ini di footer.php)