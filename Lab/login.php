<?php
session_start();
if (isset($_SESSION['user_id'])) { header('Location: '.($_SESSION['role']==='admin'?'admin.php':'dashboard.php')); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    require_once 'db.php';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $conn->prepare("SELECT id, full_name, username, password, role, status FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param('s',$username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user && $user['password'] === $password && $user['status']==='active') {
        $_SESSION['user_id']=$user['id'];
        $_SESSION['full_name']=$user['full_name'];
        $_SESSION['username']=$user['username'];
        $_SESSION['role']=$user['role'];
        header('Location: '.($user['role']==='admin'?'admin.php':'dashboard.php')); exit;
    } else { $error='Invalid username or password.'; }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>ThreadWear Login</title><link rel="stylesheet" href="styles.css"></head><body>
<div class="login-shell">
  <section class="login-copy">
    <div class="brand"><div class="logo">TW</div><div class="site-title">ThreadWear</div></div>
    <h1>Style that fits every day.</h1>
    <p>Discover clean streetwear, smart casual layers, and wardrobe essentials from one simple account.</p>
    <div class="chips"><span class="chip">New arrivals</span><span class="chip">Member picks</span><span class="chip">Easy shopping</span></div>
  </section>
  <section class="login-card">
    <div class="eyebrow">Customer sign in</div>
    <h2>Welcome back</h2>
    <p>Log in to continue to your ThreadWear dashboard.</p>
    <?php if($error): ?><div class="error-box"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <form method="post">
      <div class="field"><label>Username</label><input name="username" autocomplete="off" required></div>
      <div class="field"><label>Password</label><input type="password" name="password" required></div>
      <button class="btn" type="submit">Login</button>
    </form>
  </section>
</div>
</body></html>
