<?php
include 'db.php';

// Tambah relasi user-course
if (isset($_POST['save'])) {
    $id_user = $_POST['id_user'];
    $id_course = $_POST['id_course'];

    $conn->query("INSERT INTO userCourse (id_user, id_course)
                  VALUES ('$id_user', '$id_course')");
}

// Hapus relasi
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM userCourse WHERE id_user='$_GET[delete]'");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Relasi User-Course</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<h3>🔗 Relasi User-Course</h3>

<form method="post" class="row g-2 mb-4">
  <div class="col-md-4">
    <select name="id_user" class="form-select" required>
      <option value="">Pilih User</option>
      <?php
      $u = $conn->query("SELECT * FROM users");
      if (!$u) {
          die("Query Error (users): " . $conn->error);
      }
      while ($us = $u->fetch_assoc()) {
          echo "<option value='{$us['id_user']}'>{$us['username']}</option>";
      }
      ?>
    </select>
  </div>

  <div class="col-md-4">
    <select name="id_course" class="form-select" required>
      <option value="">Pilih Course</option>
      <?php
      $c = $conn->query("SELECT * FROM courses");
      if (!$c) {
          die("Query Error (courses): " . $conn->error);
      }
      while ($cs = $c->fetch_assoc()) {
          echo "<option value='{$cs['id_user']}'>{$cs['course']}</option>";
      }
      ?>
    </select>
  </div>

  <div class="col-md-4">
    <button class="btn btn-success" name="save">Tambah</button>
  </div>
</form>

<table class="table table-bordered table-striped">
  <tr class="table-primary text-center">
    <th>No</th><th>Username</th><th>Course</th><th>Mentor</th><th>Title</th><th>Aksi</th>
  </tr>
  <?php
  $no = 1;
  $relasi = $conn->query("
  SELECT 
  u.id AS id,
  u.username,
  c.course,
  c.mentor,
  c.title
FROM 
  userCourse uc
JOIN 
  users u ON uc.id_user = u.id
JOIN 
  courses c ON uc.id_course = c.id
ORDER BY 
  u.id, c.id;
  ");

  if (!$relasi) {
      die("Query Error (relasi): " . $conn->error);
  }

  while ($r = $relasi->fetch_assoc()) {
      echo "<tr>
        <td>{$no}</td>
        <td>{$r['username']}</td>
        <td>{$r['course']}</td>
        <td>{$r['mentor']}</td>
        <td>{$r['title']}</td>
        <td><a href='?delete={$r['username']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin hapus data ini?');\">Hapus</a></td>
      </tr>";
      $no++;
  }
  ?>
</table>

<a href="index.php" class="btn btn-secondary mt-3">⬅️ Kembali</a>
</body>
</html>
