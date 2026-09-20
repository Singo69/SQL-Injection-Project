<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
if ($_SESSION['role'] !== 'admin') { header('Location: dashboard.php'); exit; }
require_once 'db.php';
function log_action($conn,$actor,$action){ $s=$conn->prepare("INSERT INTO activity_logs(actor,action) VALUES(?,?)"); $s->bind_param('ss',$actor,$action); $s->execute(); }
$actor = $_SESSION['full_name'];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (($_POST['form_type'] ?? '') === 'user') {
        $id=(int)$_POST['id']; $full=$_POST['full_name']; $username=$_POST['username']; $email=$_POST['email']; $password=$_POST['password']; $role=$_POST['role']; $status=$_POST['status'];
        $stmt=$conn->prepare("UPDATE users SET full_name=?, username=?, email=?, password=?, role=?, status=? WHERE id=?");
        $stmt->bind_param('ssssssi',$full,$username,$email,$password,$role,$status,$id); $stmt->execute();
        log_action($conn,$actor,"Updated user record #$id");
        if ($id == $_SESSION['user_id']) { $_SESSION['full_name']=$full; $_SESSION['username']=$username; $_SESSION['role']=$role; }
    }
if (($_POST['form_type'] ?? '') === 'product') {
    $id=(int)$_POST['id']; $name=$_POST['name']; $cat=$_POST['category']; $size=$_POST['size']; $color=$_POST['color']; $stock=(int)$_POST['stock']; $price=(float)$_POST['price']; $details=$_POST['details'];
    $stmt=$conn->prepare("UPDATE products SET name=?, category=?, size=?, color=?, stock=?, price=?, details=? WHERE id=?");
    $stmt->bind_param('ssssidsi',$name,$cat,$size,$color,$stock,$price,$details,$id); $stmt->execute();
    log_action($conn,$actor,"Updated product record #$id");
}
}
$users=$conn->query("SELECT * FROM users ORDER BY id")->fetch_all(MYSQLI_ASSOC);
$products=$conn->query("SELECT * FROM products ORDER BY id")->fetch_all(MYSQLI_ASSOC);
$logs=$conn->query("SELECT * FROM activity_logs ORDER BY id DESC LIMIT 8")->fetch_all(MYSQLI_ASSOC);
$customers=$conn->query("SELECT COUNT(*) c FROM users WHERE role='customer'")->fetch_assoc()['c'];
$prodCount=$conn->query("SELECT COUNT(*) c FROM products")->fetch_assoc()['c'];
$low=$conn->query("SELECT COUNT(*) c FROM products WHERE stock <= 10")->fetch_assoc()['c'];
$orders=$conn->query("SELECT COUNT(*) c FROM orders")->fetch_assoc()['c'];
?>
<!doctype html><html><head><meta charset="utf-8"><title>ThreadWear Admin</title><link rel="stylesheet" href="styles.css"></head><body>
<header class="admin-header">
  <div class="brand"><div class="logo">TW</div><div class="admin-title"><h1>Admin Panel</h1><small>Signed in as <?=htmlspecialchars($_SESSION['full_name'])?> • Role: <?=htmlspecialchars($_SESSION['role'])?></small></div></div>
  <nav class="tabs"><a href="#users">Users</a><a href="#products">Products</a><a href="#logs">Logs</a></nav>
  <div class="admin-user"><strong><?=htmlspecialchars($_SESSION['full_name'])?></strong><small><?=htmlspecialchars($_SESSION['username'])?>@threadwear</small><br><a class="logout" href="logout.php">Logout</a></div>
</header>
<main class="admin-wrap">
  <section class="stats"><div class="stat"><span>Customers</span><strong><?=$customers?></strong></div><div class="stat"><span>Products</span><strong><?=$prodCount?></strong></div><div class="stat"><span>Low stock</span><strong><?=$low?></strong></div><div class="stat"><span>Orders</span><strong><?=$orders?></strong></div></section>
  <h2 id="users">User Management</h2>
  <section class="table-card"><table class="admin-table"><thead><tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Password</th><th>Role</th><th>Status</th><th>Action</th></tr></thead><tbody>
    <?php foreach($users as $u): ?><tr><form method="post"><input type="hidden" name="form_type" value="user"><input type="hidden" name="id" value="<?=$u['id']?>"><td><?=$u['id']?></td><td><input class="admin-input" name="full_name" value="<?=htmlspecialchars($u['full_name'])?>"></td><td><input class="admin-input" name="username" value="<?=htmlspecialchars($u['username'])?>"></td><td><input class="admin-input" name="email" value="<?=htmlspecialchars($u['email'])?>"></td><td><input class="admin-input" name="password" value="<?=htmlspecialchars($u['password'])?>"></td><td><select class="admin-select" name="role"><option <?=$u['role']=='customer'?'selected':''?>>customer</option><option <?=$u['role']=='admin'?'selected':''?>>admin</option></select></td><td><select class="admin-select" name="status"><option <?=$u['status']=='active'?'selected':''?>>active</option><option <?=$u['status']=='inactive'?'selected':''?>>inactive</option></select></td><td><button class="btn save">Save</button></td></form></tr><?php endforeach; ?>
  </tbody></table></section>
  <h2 id="products">Stock Management</h2>
  <section class="table-card"><table class="admin-table"><thead><tr><th>ID</th><th>Product</th><th>Category</th><th>Size</th><th>Color</th><th>Stock</th><th>Price</th><th>Details</th><th>Action</th></tr></thead><tbody>
    <?php foreach($products as $p): ?><tr><form method="post"><input type="hidden" name="form_type" value="product"><input type="hidden" name="id" value="<?=$p['id']?>"><td><?=$p['id']?></td><td><input class="admin-input" name="name" value="<?=htmlspecialchars($p['name'])?>"></td><td><input class="admin-input" name="category" value="<?=htmlspecialchars($p['category'])?>"></td><td><input class="admin-input" name="size" value="<?=htmlspecialchars($p['size'])?>"></td><td><input class="admin-input" name="color" value="<?=htmlspecialchars($p['color'])?>"></td><td><input class="admin-input" name="stock" value="<?=htmlspecialchars($p['stock'])?>"></td><td><input class="admin-input" name="price" value="<?=htmlspecialchars($p['price'])?>"></td><td><input class="admin-input" name="details" value="<?=htmlspecialchars($p['details'])?>"></td><td><button class="btn save">Save</button></td></form></tr><?php endforeach; ?>
  </tbody></table></section>
  <h2 id="logs">Activity Logs</h2>
  <section class="table-card"><table class="admin-table"><thead><tr><th>ID</th><th>Actor</th><th>Action</th><th>Time</th></tr></thead><tbody><?php foreach($logs as $l): ?><tr><td><?=$l['id']?></td><td><?=htmlspecialchars($l['actor'])?></td><td><?=htmlspecialchars($l['action'])?></td><td><?=htmlspecialchars($l['created_at'])?></td></tr><?php endforeach; ?></tbody></table></section>
</main></body></html>
