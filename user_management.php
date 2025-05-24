<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
$current = basename($_SERVER['PHP_SELF']);

// Connect to DB (update with your credentials)
$mysqli = new mysqli("localhost", "root", "", "miakape");
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Fetch all users
$result = $mysqli->query("SELECT id, username, role FROM users ORDER BY id ASC");
$users = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Management - Mia's Kape</title>
  <link rel="stylesheet" href="assets/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <h2 class="brand"><i>Mia's</i> Kape</h2>
      <nav class="nav-links">
        <a href="admin.php" class="<?= $current === 'admin.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="user_management.php" class="<?= $current === 'user_management.php' ? 'active' : '' ?>">Users</a>
        <a href="products.php" class="<?= $current === 'products.php' ? 'active' : '' ?>">Products</a>
        <a href="purchases.php" class="<?= $current === 'purchases.php' ? 'active' : '' ?>">Purchases</a>
        <a href="sales.php" class="<?= $current === 'sales.php' ? 'active' : '' ?>">Sales</a>
        <a href="returns.php" class="<?= $current === 'returns.php' ? 'active' : '' ?>">Returns</a>
      </nav>
    </aside>
    <main class="main-content">
      <header class="topbar">
        <div class="admin-actions">
          <button id="adminButton"><?= htmlspecialchars($username) ?> ▾</button>
          <div class="dropdown hidden" id="adminDropdown">
            <a href="logout.php">Logout</a>
          </div>
        </div>
      </header>
      <section class="content">
        <h2>User Management</h2>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Role</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="user-table-body">
            <?php foreach ($users as $user): ?>
              <tr data-id="<?= $user['id'] ?>" data-username="<?= htmlspecialchars($user['username']) ?>" data-role="<?= $user['role'] ?>">
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                  <button type="button" class="btn-add" onclick="resetForm()">Add</button>
                  <button class="btn-edit" onclick="editUser(<?= $user['id'] ?>)">Edit</button>
                  <button class="btn-delete" onclick="deleteUser(<?= $user['id'] ?>)">Delete</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        
        <h3 id="form-title"></h3>
       <form id="user-form" method="POST" action="create_user.php">
  <input type="hidden" name="id" id="user-id" value="" />
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required />
  </div>
  <div class="form-group">
    <label for="password">Password <small>(leave blank to keep current password when editing)</small></label>
    <input type="password" id="password" name="password" />
  </div>
  <div class="form-group">
    <label for="role">Role</label>
    <select id="role" name="role" required>
      <option value="">-- Select Role --</option>
      <option value="Admin">Admin</option>
      <option value="Cashier">Cashier</option>
    </select>
  </div>
  <input type="submit" value="Add User" />
</form>
      </section>
    </main>
  </div>
  <script>
    document.getElementById('adminButton').addEventListener('click', function() {
      document.getElementById('adminDropdown').classList.toggle('hidden');
    });
  </script>
  <script src="user_management.js"></script>
</body>
</html>