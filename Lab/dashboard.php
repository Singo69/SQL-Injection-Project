<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
if ($_SESSION['role']==='admin') { header('Location: admin.php'); exit; }
require_once 'db.php';
$q = $_GET['q'] ?? '';
$displayError = '';
$rows = [];
$productCategories = ['Pants','Cardigans','Tshirts','Jackets'];
mysqli_report(MYSQLI_REPORT_OFF);
if ($q === '') {
    $sql = "SELECT id, name, category, CONCAT('Size: ',size,' | Color: ',color,' | Stock: ',stock,' | ',details) AS details, price FROM products ORDER BY id LIMIT 10";
} else {
    $sql = "SELECT id, name, category, CONCAT('Size: ',size,' | Color: ',color,' | Stock: ',stock,' | ',details) AS details, price FROM products WHERE name LIKE '%$q%' OR category LIKE '%$q%' OR details LIKE '%$q%' ORDER BY id";
}
$res = $conn->query($sql);
if ($res) { while($r=$res->fetch_assoc()) { $rows[]=$r; } }
else { $displayError = $conn->error; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>ThreadWear Dashboard</title><link rel="stylesheet" href="styles.css"></head><body class="<?= $q !== '' ? 'search-mode' : '' ?>">
<header class="topbar">
  <div class="brand"><div class="logo">TW</div><div class="site-title">ThreadWear</div></div>
  <nav class="nav"><a href="dashboard.php?q=Tshirts">Tshirts</a><a href="dashboard.php?q=Pants">Pants</a><a href="dashboard.php?q=Cardigans">Cardigans</a><a href="dashboard.php?q=Jackets">Jackets</a></nav>
  <div class="profile user-pill">
    <div class="profile-text"><strong>Hello <?=htmlspecialchars(explode(' ', $_SESSION['full_name'])[0])?></strong><small><?=htmlspecialchars($_SESSION['role'])?></small></div>
    <a class="logout-btn" href="logout.php">Logout</a>
  </div>
</header>
<main class="container">
  <section class="hero"><h1>New season essentials</h1><p>Explore clean streetwear, elevated basics, and smart casual pieces built for everyday outfits.</p></section>
  <section class="search-panel">
    <h2>Product Search</h2>
    <form class="search-row" method="get" action="dashboard.php">
      <input name="q" value="<?=htmlspecialchars($q)?>" placeholder="Search products, categories or details">
      <button type="submit">Search</button><a class="btn secondary" href="dashboard.php">Clear</a>
    </form>
    <?php if($displayError): ?><div class="error-box">Search error: <?=htmlspecialchars($displayError)?></div><?php endif; ?>
  </section>
  <div class="section-head"><h2>Products</h2><div class="count"><?=count($rows)?> result<?=count($rows)==1?'':'s'?></div></div>
  <section class="table-card"><table><thead><tr><th class="td-id">ID</th><th>Product</th><th>Category</th><th>Details</th><th>Price</th><th>Action</th></tr></thead><tbody>
    <?php foreach($rows as $r): ?>
    <?php
      $isProductRow = in_array((string)$r['category'], $productCategories, true)
        && isset($r['details']) && str_starts_with((string)$r['details'], 'Size:')
        && is_numeric($r['price']);
    ?>
    <tr class="<?=$isProductRow ? 'product-row' : 'evidence-row'?>"><td><?=htmlspecialchars($r['id'])?></td><td class="name-cell"><strong><?=htmlspecialchars($r['name'])?></strong></td><td><?=htmlspecialchars($r['category'])?></td><td class="name-cell"><span><?=htmlspecialchars($r['details'])?></span></td><td class="price"><?php if($isProductRow): ?>$<?=number_format((float)$r['price'],2)?><?php else: ?><span class="muted-dash">—</span><?php endif; ?></td><td><?php if($isProductRow): ?><button class="buy">Buy</button><?php else: ?><span class="muted-dash">—</span><?php endif; ?></td></tr>
    <?php endforeach; ?>
    <?php if(!$rows && !$displayError): ?><tr><td colspan="6">No products found.</td></tr><?php endif; ?>
  </tbody></table></section>
</main></body></html>
