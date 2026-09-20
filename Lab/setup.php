<?php
$host='127.0.0.1'; $port=3307; $user='root'; $pass=''; $dbname='threadwear_coursework_v4';
try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli($host,$user,$pass,'',$port);
    $conn->set_charset('utf8mb4');
    $conn->query("DROP DATABASE IF EXISTS `$dbname`");
    $conn->query("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $conn->select_db($dbname);

    $conn->query("CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(120) NOT NULL,
        password VARCHAR(100) NOT NULL,
        role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
        status ENUM('active','inactive') NOT NULL DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $conn->query("CREATE TABLE products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        category VARCHAR(50) NOT NULL,
        size VARCHAR(30) NOT NULL,
        color VARCHAR(50) NOT NULL,
        stock INT NOT NULL,
        price DECIMAL(8,2) NOT NULL,
        details VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB");

    $conn->query("CREATE TABLE orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        customer_name VARCHAR(100),
        product_name VARCHAR(120),
        quantity INT,
        status VARCHAR(30),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $conn->query("CREATE TABLE activity_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        actor VARCHAR(100),
        action VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $users = [
        ['Ayudh Dahal','ayudh','ayudh@threadwear.local','user123','customer','active'],
        ['Aayush Bista','aayush','aayush@threadwear.local','user123','customer','active'],
        ['Pawan Regami Magar','pawan','pawan@threadwear.local','user123','customer','active'],
        ['Subhash Bubha Magar','subhash','subhash@threadwear.local','user123','customer','active'],
        ['ThreadWear Admin','admin','admin@threadwear.local','admin123','admin','active']
    ];
    $stmt=$conn->prepare("INSERT INTO users(full_name,username,email,password,role,status) VALUES (?,?,?,?,?,?)");
    foreach($users as $u){ $stmt->bind_param('ssssss',...$u); $stmt->execute(); }

    $products = [
        ['Baggy Jeans','Pants','S-XL','Olive',18,54.99,'Relaxed denim with clean streetwear shape.'],
        ['Linen Pants','Pants','M-XXL','Sand',13,48.50,'Lightweight smart-casual summer trousers.'],
        ['Straight Fit Jeans','Pants','S-XL','Indigo',9,59.99,'Classic straight fit denim for daily wear.'],
        ['Cargo Pants','Pants','M-XL','Black',7,62.00,'Utility pockets with durable cotton blend.'],
        ['Ribbed Cardigan','Cardigans','S-L','Cream',5,45.99,'Soft ribbed knit layering piece.'],
        ['Oversized Cardigan','Cardigans','M-XXL','Charcoal',11,52.99,'Warm oversized knit for casual outfits.'],
        ['Classic Tee','Tshirts','S-XXL','White',25,19.99,'Everyday cotton t-shirt with regular fit.'],
        ['Graphic Tee','Tshirts','S-XL','Black',15,24.99,'Printed streetwear tee with relaxed fit.'],
        ['Heavyweight Tee','Tshirts','M-XXL','Navy',10,29.99,'Thick cotton tee for premium basics.'],
        ['Bomber Jacket','Jackets','M-XL','Brown',4,89.99,'Light bomber jacket with clean finish.']
    ];
    $stmt=$conn->prepare("INSERT INTO products(name,category,size,color,stock,price,details) VALUES (?,?,?,?,?,?,?)");
    foreach($products as $p){ $stmt->bind_param('ssssids',$p[0],$p[1],$p[2],$p[3],$p[4],$p[5],$p[6]); $stmt->execute(); }

    $conn->query("INSERT INTO orders(customer_name,product_name,quantity,status) VALUES
        ('Ayudh Dahal','Baggy Jeans',1,'Processing'),('Aayush Bista','Classic Tee',2,'Completed'),('Pawan Regami Magar','Bomber Jacket',1,'Pending')");
    $conn->query("INSERT INTO activity_logs(actor,action) VALUES
        ('system','Initial product catalogue loaded'),('system','Five test accounts created'),('admin','Stock review completed')");

    echo '<!doctype html><html><head><link rel="stylesheet" href="styles.css"><title>Setup</title></head><body><div class="setup-box"><h1>Setup complete</h1><p>Database <b>'.$dbname.'</b> has been created successfully.</p><a class="btn" href="login.php">Open login</a></div></body></html>';
} catch (Throwable $e) {
    echo '<h2>Setup failed</h2><p>'.htmlspecialchars($e->getMessage()).'</p>';
}
?>
