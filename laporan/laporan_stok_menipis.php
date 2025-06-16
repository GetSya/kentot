<?php
$title = "Laporan Stok Menipis";
include __DIR__ . '/../template/header.php';

// Hak akses admin
if ($_SESSION['role'] != 'administrator') {
    echo "<script>alert('Anda tidak punya akses ke halaman ini!'); window.location.href='/aca_pos/dashboard.php';</script>";
    exit;
}
include __DIR__ . '/../template/sidebar.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Laporan Stok Menipis</h1>
    <p>Daftar item yang stoknya telah mencapai atau di bawah batas minimum.</p>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Item</th>
                            <th>Nama Item</th>
                            <th>Stok Saat Ini</th>
                            <th>Stok Minimum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT kode_item, nama_item, stok, stok_minimum FROM item WHERE stok <= stok_minimum ORDER BY stok ASC";
                        $result = mysqli_query($koneksi, $query);
                        $no = 1;
                        if(mysqli_num_rows($result) > 0) {
                            while($data = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr class="table-danger">
                                <td><?= $no++; ?></td>
                                <td><?= $data['kode_item']; ?></td>
                                <td><?= $data['nama_item']; ?></td>
                                <td><?= $data['stok']; ?></td>
                                <td><?= $data['stok_minimum']; ?></td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center">Tidak ada item dengan stok menipis.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/../template/footer.php';
?>