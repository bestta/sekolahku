<?php
include 'db.php';

if (isset($_POST['save'])) {
    $conn->query("INSERT INTO courses (course, mentor, title)
                  VALUES ('$_POST[course]', '$_POST[mentor]', '$_POST[title]')");
}
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM courses WHERE id='$_GET[delete]'");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>CRUD Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<h3>📘 CRUD Courses</h3>
<form method="post" class="row g-2 mb-4">
  <div class="col-md-3"><input type="text" name="course" class="form-control" placeholder="Nama Course" required></div>
  <div class="col-md-3"><input type="text" name="mentor" class="form-control" placeholder="Mentor"></div>
  <div class="col-md-3"><input type="text" name="title" class="form-control" placeholder="Title (Dr./S.Kom/M.T.)"></div>
  <div class="col-md-3"><button class="btn btn-success" name="save">Tambah</button></div>
</form>

<table class="table table-bordered table-striped">
  <tr class="table-primary text-center">
    <th>No</th><th>Course</th><th>Mentor</th><th>Title</th><th>Aksi</th>
  </tr>
  <?php
  $no = 1;
  $data = $conn->query("SELECT * FROM courses");
  while ($c = $data->fetch_assoc()) {
    echo "<tr>
      <td>$no</td>
      <td>$c[course]</td>
      <td>$c[mentor]</td>
      <td>$c[title]</td>
      <td><a href='?delete=$c[id]' class='btn btn-danger btn-sm'>Hapus</a></td>
    </tr>";
    $no++;
  }
  ?>
</table>
<a href="index.php" class="btn btn-secondary mt-3">⬅️ Kembali</a>
</body>
</html>
