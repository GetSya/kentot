<?php
// File ini hanya untuk membuat hash password baru.
// Jangan unggah file ini ke server produksi.

if (isset($_POST['password'])) {
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    echo "<h2>Password Anda: " . htmlspecialchars($password) . "</h2>";
    echo "<h3>Hash yang Dihasilkan:</h3>";
    echo "<textarea rows='3' style='width: 100%;' readonly>" . $hash . "</textarea>";
    echo "<p><strong>COPY</strong> seluruh hash di atas dan paste ke kolom 'password' di database phpMyAdmin untuk user 'admin'.</p>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Generator Hash Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h1>Generator Hash Password</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="password" class="form-label">Masukkan Password Baru:</label>
            <input type="text" name="password" id="password" class="form-control" value="admin123" required>
        </div>
        <button type="submit" class="btn btn-primary">Generate Hash</button>
    </form>
</body>
</html>