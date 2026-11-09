<?php
session_start();

// Cek session login
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}

// Memanggil koneksi database
require_once __DIR__ . '/../includes/koneksi.php';

// Daftar jenis kendaraan
$jenis_valid = ['Sedan', 'Pickup', 'Truk', 'Bemo'];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mengambil data dari form
    $nomor   = trim($_POST['nomor_kendaraan'] ?? '');
    $jenis   = trim($_POST['jenis_kendaraan'] ?? '');
    $pemilik = trim($_POST['pemilik'] ?? '');
    $tahun   = trim($_POST['tahun_pembuatan'] ?? '');

    // Jenis kendaraan yang diperbolehkan
    $jenis_valid = ['Sedan', 'Pickup', 'Truk', 'Bemo'];

    // Validasi input
    if ($nomor === '') {
        $errors[] = 'Nomor kendaraan wajib diisi.';
    }

    if (!in_array($jenis, $jenis_valid, true)) {
        $errors[] = 'Jenis kendaraan tidak valid.';
    }

    if ($pemilik === '') {
        $errors[] = 'Pemilik wajib diisi.';
    }

    if (!preg_match('/^\d{4}$/', $tahun)) {
        $errors[] = 'Tahun pembuatan harus 4 digit angka.';
    }

    // Jika tidak ada error, simpan data
    if (empty($errors)) {

        $sql = "INSERT INTO mobil015
                (nomor_kendaraan, jenis_kendaraan, pemilik, tahun_pembuatan)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "ssss",
                $nomor,
                $jenis,
                $pemilik,
                $tahun
            );

            if (mysqli_stmt_execute($stmt)) {
                header('Location: index.php?pesan=tambah');
                exit;
            } else {
                $errors[] = 'Gagal menyimpan data: ' . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);

        } else {
            $errors[] = 'Query database gagal: ' . mysqli_error($conn);
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">

        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="index.php">Kendaraan</a>
                </li>
                <li class="breadcrumb-item active">
                    Tambah
                </li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-dark text-white fw-semibold">
                <i class="bi bi-plus-circle me-1 text-warning"></i>
                Tambah Kendaraan
            </div>

            <div class="card-body">

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger py-2 small">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $e): ?>
                                <li>
                                    <?= htmlspecialchars($e) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" novalidate>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            Nomor Kendaraan
                        </label>

                        <input
                            type="text"
                            name="nomor_kendaraan"
                            maxlength="12"
                            class="form-control"
                            placeholder="cth. B 1234 XYZ"
                            value="<?= htmlspecialchars($_POST['nomor_kendaraan'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            Jenis Kendaraan
                        </label>

                        <select
                            name="jenis_kendaraan"
                            class="form-select"
                            required
                        >
                            <option value="">-- Pilih Jenis --</option>

                            <?php foreach ($jenis_valid as $j): ?>
                                <option
                                    value="<?= htmlspecialchars($j) ?>"
                                    <?= (($_POST['jenis_kendaraan'] ?? '') === $j) ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($j) ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            Pemilik
                        </label>

                        <input
                            type="text"
                            name="pemilik"
                            maxlength="50"
                            class="form-control"
                            placeholder="Nama pemilik"
                            value="<?= htmlspecialchars($_POST['pemilik'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">
                            Tahun Pembuatan
                        </label>

                        <input
                            type="text"
                            name="tahun_pembuatan"
                            maxlength="4"
                            class="form-control"
                            placeholder="cth. 2020"
                            value="<?= htmlspecialchars($_POST['tahun_pembuatan'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-save me-1"></i>
                            Simpan
                        </button>

                        <a href="index.php" class="btn btn-outline-secondary">
                            Batal
                        </a>

                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

