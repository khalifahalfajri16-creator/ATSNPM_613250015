<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}
require_once 'includes/koneksi.php';

// Hitung total kendaraan — ganti mobil015 sesuai NPM Anda
$total = 0;
$res   = mysqli_query($conn, "SELECT COUNT(*) AS total FROM mobil015");
if ($res) {
    $total = mysqli_fetch_assoc($res)['total'];
}
?>
<?php include 'includes/header.php'; ?>

<div class="row mb-4">
  <div class="col">
    <h4 class="fw-bold"><i class="bi bi-speedometer2 text-warning me-1"></i> Dashboard</h4>
    <p class="text-muted">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>!</p>
  </div>
</div>

<div class="row g-4">
  <!-- Kartu total kendaraan -->
  <div class="col-sm-6 col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="rounded-circle bg-warning bg-opacity-15 p-3">
          <i class="bi bi-car-front-fill text-warning fs-3"></i>
        </div>
        <div>
          <div class="text-muted small">Total Kendaraan</div>
          <div class="fs-2 fw-bold"><?= $total ?></div>
        </div>
      </div>
      <div class="card-footer bg-transparent border-0">
        <a href="kendaraan/index.php" class="btn btn-sm btn-outline-dark w-100">
          <i class="bi bi-arrow-right-circle me-1"></i> Kelola Data
        </a>
      </div>
    </div>
  </div>

  <!-- Shortcut tambah -->
  <div class="col-sm-6 col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="rounded-circle bg-success bg-opacity-15 p-3">
          <i class="bi bi-plus-circle-fill text-success fs-3"></i>
        </div>
        <div>
          <div class="text-muted small">Tambah Kendaraan</div>
          <div class="text-secondary small">Input data baru</div>
        </div>
      </div>
      <div class="card-footer bg-transparent border-0">
        <a href="kendaraan/tambah.php" class="btn btn-sm btn-outline-success w-100">
          <i class="bi bi-plus-lg me-1"></i> Tambah
        </a>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
