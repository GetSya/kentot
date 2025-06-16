<?php
$title = "Manajemen User";
include __DIR__ . '/../template/header.php';

// Proteksi Halaman: Hanya untuk Administrator
if ($_SESSION['role'] != 'administrator') {
    // Arahkan ke dashboard jika bukan admin
    echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location.href='/aca_pos/dashboard.php';</script>";
    exit;
}

include __DIR__ . '/../template/sidebar.php';
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen User</h1>
        <!-- Tombol untuk memunculkan modal tambah user -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
            <i class="bi bi-person-plus-fill"></i> Tambah User
        </button>
    </div>

    <!-- Menampilkan pesan sukses atau error -->
    <?php if (isset($_GET['sukses'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['sukses']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY nama_lengkap ASC");
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($data['nama_lengkap']); ?></td>
                                <td><?= htmlspecialchars($data['username']); ?></td>
                                <td><span class="badge bg-<?= $data['role'] == 'administrator' ? 'success' : 'info' ?>"><?= ucfirst($data['role']); ?></span></td>
                                <td>
                                    <?php if ($data['id_user'] != $_SESSION['id_user']): // Tombol hapus tidak muncul untuk user yang sedang login ?>
                                        <a href="hapus_user.php?id=<?= $data['id_user']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>Hapus</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah User (Menggantikan halaman tambah_user.php) -->
<div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahUserModalLabel">Tambah User Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Formnya mengarah ke proses_tambah_user.php -->
      <form action="proses_tambah_user.php" method="POST">
        <div class="modal-body">
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
             <div class="mb-3">
                <label for="password" class="form-label">Password</for>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
             <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" name="role" id="role" required>
                    <option value="" disabled selected>-- Pilih Role --</option>
                    <option value="kasir">Kasir</option>
                    <option value="administrator">Administrator</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" name="tambah_user" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
include __DIR__ . '/../template/footer.php';
?>