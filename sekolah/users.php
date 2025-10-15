<?php
include 'db.php';

// ➕ Tambah Data User
if (isset($_POST['save'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($username && $email && $password) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, created_at, updated_at)
                                VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            echo "<script>alert('✅ User berhasil ditambahkan!'); window.location='users.php';</script>";
        } else {
            echo "<script>alert('❌ Gagal menambahkan user!');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('⚠️ Semua field harus diisi!');</script>";
    }
}


// 🧠 Ambil data user untuk edit
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = $conn->query("SELECT * FROM users WHERE id='$id'");
    $editData = $res->fetch_assoc();
}

// ✏️ Update Data User
if (isset($_POST['update'])) {
    $id       = $_POST['id'];
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($password)) {
        // Jika password diisi → simpan apa adanya
        $stmt = $conn->prepare("UPDATE users 
                                SET username = ?, email = ?, password = ?, updated_at = NOW() 
                                WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $password, $id);
    } else {
        // Jika password kosong → jangan ubah password
        $stmt = $conn->prepare("UPDATE users 
                                SET username = ?, email = ?, updated_at = NOW() 
                                WHERE id = ?");
        $stmt->bind_param("ssi", $username, $email, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('✅ User berhasil diperbarui!'); window.location='users.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal memperbarui user!');</script>";
    }

    $stmt->close();
}


// ❌ Hapus Data User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id='$id'");
    echo "<script>alert('User berhasil dihapus!'); window.location='users.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>CRUD Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h3 class="text-center mb-4">👤 CRUD Users</h3>

  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
      <?= $editData ? 'Edit User' : 'Tambah User'; ?>
    </div>
    <div class="card-body">
  <form method="post" class="row g-2">
    <input type="hidden" name="id" value="<?php echo isset($editData['id']) ? $editData['id'] : ''; ?>">

    <div class="col-md-3">
      <input type="text" name="username" class="form-control" placeholder="Username"
             value="<?php echo isset($editData['username']) ? $editData['username'] : ''; ?>" required>
    </div>
    <div class="col-md-3">
      <input type="email" name="email" class="form-control" placeholder="Email"
             value="<?php echo isset($editData['email']) ? $editData['email'] : ''; ?>" required>
    </div>
    <div class="col-md-3">
      <input type="password" name="password" class="form-control" placeholder="Password"
            value="<?php echo isset($editData['password']) ? $editData['password'] : ''; ?>" required>
    </div>
    <div class="col-md-3">
      <?php if ($editData): ?>
        <button type="submit" name="update" class="btn btn-warning w-100">Update</button>
        <a href="users.php" class="btn btn-secondary w-100 mt-2">Batal</a>
      <?php else: ?>
        <button type="submit" name="save" class="btn btn-success w-100">Tambah</button>
      <?php endif; ?>
    </div>
  </form>
</div>

  </div>

  <table class="table table-bordered table-striped table-hover align-middle">
    <thead class="table-primary text-center">
      <tr>
        <th>No</th>
        <th>Username</th>
        <th>Email</th>
        <th>Password (hash)</th>
        <th>Created</th>
        <th>Updated</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
      while ($row = $result->fetch_assoc()):
      ?>
        <tr>
          <td class="text-center"><?= $no++; ?></td>
          <td><?= $row['username']; ?></td>
          <td><?= $row['email']; ?></td>
          <td><code><?= substr($row['password'], 0, 25) ?>*****</code></td>
          <td><?= $row['created_at']; ?></td>
          <td><?= $row['updated_at']; ?></td>
          <td class="text-center">
            <a href="?edit=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="?delete=<?= $row['id']; ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Yakin ingin menghapus user ini?');">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
