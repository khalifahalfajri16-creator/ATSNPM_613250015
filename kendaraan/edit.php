<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Ambil data yang akan diedit — ganti mobil015 sesuai NPM Anda
$res  = mysqli_query($conn, "SELECT * FROM mobil015 WHERE no_id = $id LIMIT 1");
if (!$res || mysqli_num_rows($res) === 0) {
    header('Location: index.php');
    exit;
}
$kendaraan = mysqli_fetch_assoc($res);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor   = trim($_POST['nomor_kendaraan'] ?? '');
    $jenis   = trim($_POST['jenis_kendaraan'] ?? '');
    $pemilik = trim($_POST['pemilik'] ?? '');
    $tahun   = trim($_POST['tahun_pembuatan'] ?? '');

    $jenis_valid = ['Sedan', 'Pickup', 'Truk', 'Bemo'];

    if ($nomor   === '')                        $errors[] = 'Nomor kendaraan wajib diisi.';
    if (!in_array($jenis, $jenis_valid, true))  $errors[] = 'Jenis kendaraan tidak valid.';
    if ($pemilik === '')                        $errors[] = 'Pemilik wajib diisi.';
    if (!preg_match('/^\d{4}$/', $tahun))       $errors[] = 'Tahun pembuatan harus 4 digit angka.';

    if (empty($errors)) {
        $n = mysqli_real_escape_string($conn, $nomor);
        $j = mysqli_real_escape_string($conn, $jenis);
        $p = mysqli_real_escape_string($conn, $pemilik);
        $t = mysqli_real_escape_string($conn, $tahun);

        $sql = "UPDATE mobil015
                   SET nomor_kendaraan='$n',
                       jenis_kendaraan='$j',
                       pemilik='$p',
                       tahun_pembuatan='$t'
                 WHERE no_id=$id";

        if (mysqli_query($conn, $sql)) {
            header('Location: index.php?pesan=edit');
            exit;
        } else {
            $errors[] = 'Gagal memperbarui data: ' . mysqli_error($conn);
        }
    }

    // Perbarui nilai untuk ditampilkan kembali di form
    $kendaraan['nomor_kendaraan'] = $nomor;
    $kendaraan['jenis_kendaraan'] = $jenis;
    $kendaraan['pemilik']         = $pemilik;
    $kendaraan['tahun_pembuatan'] = $tahun;
}
?>
<?php include '../includes/header.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">

    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Kendaraan</a></li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>

    <div class="card border-0 shadow-sm">
      <div class="card-header bg-primary text-white fw-semibold">
        <i class="bi bi-pencil-square me-1"></i> Edit Kendaraan
      </div>
      <div class="card-body">

        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger py-2 small">
            <ul class="mb-0 ps-3">
              <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form method="post" novalidate>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Nomor Kendaraan</label>
            <input type="text" name="nomor_kendaraan" maxlength="12"
                   class="form-control"
                   value="<?= htmlspecialchars($kendaraan['nomor_kendaraan']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold">Jenis Kendaraan</label>
            <select name="jenis_kendaraan" class="form-select" required>
              <?php foreach (['Sedan','Pickup','Truk','Bemo'] as $j): ?>
                <option value="<?= $j ?>"
                  <?= ($kendaraan['jenis_kendaraan'] === $j) ? 'selected' : '' ?>>
                  <?= $j ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold">Pemilik</label>
            <input type="text" name="pemilik" maxlength="50"
                   class="form-control"
                   value="<?= htmlspecialchars($kendaraan['pemilik']) ?>" required>
          </div>

          <div class="mb-4">
            <label class="form-label small fw-semibold">Tahun Pembuatan</label>
            <input type="text" name="tahun_pembuatan" maxlength="4"
                   class="form-control"
                   value="<?= htmlspecialchars($kendaraan['tahun_pembuatan']) ?>" required>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save me-1"></i> Update
            </button>
            <a href="index.php" class="btn btn-outline-secondary">Batal</a>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
