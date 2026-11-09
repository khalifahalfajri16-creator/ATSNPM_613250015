<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../includes/koneksi.php';

$sql  = "SELECT * FROM mobil015 ORDER BY no_id ASC";
$res  = mysqli_query($conn, $sql);
$data = [];
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
}

$pesan = $_GET['pesan'] ?? '';
?>
<?php include '../includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-car-front text-warning me-1"></i> Data Kendaraan</h4>
  <a href="tambah.php" class="btn btn-dark btn-sm">
    <i class="bi bi-plus-lg me-1"></i> Tambah
  </a>
</div>

<?php if ($pesan === 'tambah'): ?>
  <div class="alert alert-success alert-dismissible fade show py-2 small">
    <i class="bi bi-check-circle-fill"></i> Data berhasil ditambahkan.
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
  </div>
<?php elseif ($pesan === 'edit'): ?>
  <div class="alert alert-info alert-dismissible fade show py-2 small">
    <i class="bi bi-pencil-fill"></i> Data berhasil diperbarui.
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
  </div>
<?php elseif ($pesan === 'hapus'): ?>
  <div class="alert alert-warning alert-dismissible fade show py-2 small">
    <i class="bi bi-trash-fill"></i> Data berhasil dihapus.
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-striped align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th class="ps-3">#</th>
            <th>Nomor Kendaraan</th>
            <th>Jenis</th>
            <th>Pemilik</th>
            <th>Tahun</th>
            <th class="text-center pe-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($data)): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data kendaraan.</td></tr>
          <?php else: ?>
            <?php foreach ($data as $i => $row): ?>
            <tr>
              <td class="ps-3"><?= $i + 1 ?></td>
              <td><span class="badge bg-secondary"><?= htmlspecialchars($row['nomor_kendaraan']) ?></span></td>
              <td><?= htmlspecialchars($row['jenis_kendaraan']) ?></td>
              <td><?= htmlspecialchars($row['pemilik']) ?></td>
              <td><?= htmlspecialchars($row['tahun_pembuatan']) ?></td>
              <td class="text-center pe-3">
                <a href="edit.php?id=<?= $row['no_id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                  <i class="bi bi-pencil-fill"></i>
                </a>
                <a href="hapus.php?id=<?= $row['no_id'] ?>"
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                  <i class="bi bi-trash-fill"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
