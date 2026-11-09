<?php
session_start();
// Sudah login → langsung ke dashboard
if (isset($_SESSION['id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once 'includes/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi.';
    } else {
        $email_safe = mysqli_real_escape_string($conn, $email);
        $pass_md5   = md5($password);

        $sql  = "SELECT id, email, nama FROM member_015
                  WHERE email = '$email_safe'
                    AND password = '$pass_md5'
                    AND aktive = '1'
                  LIMIT 1";
        $res  = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) === 1) {
            $row = mysqli_fetch_assoc($res);
            $_SESSION['id']    = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['nama']  = $row['nama'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email atau password salah, atau akun tidak aktif.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login – Rental Mobil Khalifah Alfajri Annebil </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #212529; min-height: 100vh; display: flex; align-items: center; }
    .card { border-radius: 1rem; }
  </style>
</head>
<body>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">

      <div class="text-center mb-4 text-white">
        <i class="bi bi-car-front-fill text-warning" style="font-size:3rem;"></i>
        <h4 class="mt-2 fw-bold">Rental Mobil Alif</h4>
        <p class="text-secondary small">Silakan login untuk melanjutkan</p>
      </div>

      <div class="card shadow-lg">
        <div class="card-body p-4">
          <h5 class="card-title mb-3 fw-bold"><i class="bi bi-lock-fill me-1 text-warning"></i> Login</h5>

          <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show py-2 small">
              <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form method="post" novalidate>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="contoh@email.com" required autofocus>
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label small fw-semibold">Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key"></i></span>
                <input type="password" name="password" class="form-control"
                       placeholder="••••••••" required>
              </div>
            </div>
            <button type="submit" class="btn btn-dark w-100 fw-semibold">
              <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
