
<?php

include 'header.php';

$host = "192.168.192.73";
$username = "nbuser";
$password = "abc12345";
$database = "cloud";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selectedCategory = $_GET['category'] ?? "All";
$searchQuery = $_GET['search'] ?? "";

// 获取所有分类信息，同时建立映射
$category_sql = "SELECT * FROM category";
$category_result = $conn->query($category_sql);
$categories = ["All"];
$categoryMap = [];

while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row['catName'];
    $categoryMap[$row['catName']] = $row['catID'];
}

// 根据分类筛选产品
$likeSearch = '%' . $searchQuery . '%';

if ($selectedCategory !== 'All') {
    $product_sql = "SELECT p.*, c.catName 
                    FROM product p 
                    JOIN category c ON FIND_IN_SET(c.catID, p.category)
                    WHERE p.status = 1
                    AND c.catName = ?
                    AND p.productName LIKE ?";
    $stmt = $conn->prepare($product_sql);
    $stmt->bind_param("ss", $selectedCategory, $likeSearch);
} else {
    $product_sql = "SELECT DISTINCT p.* 
                    FROM product p 
                    WHERE p.status = 1
                    AND p.productName LIKE ?";
    $stmt = $conn->prepare($product_sql);
    $stmt->bind_param("s", $likeSearch);
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// 分页
$perPage = 12;
$totalProducts = count($products);
$totalPages = ceil($totalProducts / $perPage);
$page = isset($_GET['page']) ? max(1, min($_GET['page'], $totalPages)) : 1;
$startIndex = ($page - 1) * $perPage;
$displayProducts = array_slice($products, $startIndex, $perPage);
$selectedCategoryID = $categoryMap[$selectedCategory] ?? null;
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Graduation Bouquets</title>
        <style>
            body { 
                font-family: Arial, sans-serif;
                margin: auto; 
                padding: 0;
                overflow-x: hidden;  
                display: flex; 
                flex-direction: column;  
                min-height: 100vh; 
            }

            .content { 
                overflow-y: auto;
                overflow-x: auto;
                flex: 1;
                margin-top: 80px; 
                margin-bottom: 20px;
                margin-left:100px;
                margin-right:100px; 
                padding: 0; 
                display: flex;  
                gap: 10px; 
            }
            
            .sidebar { 
                width: 250px; 
                padding: 10px; 
                background: #f8f8f8; 
            }

            .sidebar ul { 
                list-style-type: none; 
                padding: 0; 
            } 

            .sidebar li { 
                margin-bottom: 10px; 
            
            } 

            .sidebar a {
                font-size: 18px; 
                color: #333;
                padding-bottom: 2px; 
            }

            .sidebar a:hover {
                color: #28a745; /* Change text color on hover */
                border-color: #28a745; /* Change the underline color on hover */
                font-weight: bold;
                
            }

            .product-container { 
                display: grid;
                margin-left:30px; 
                grid-template-columns: repeat(4, 1fr); 
                gap: 30px; 
                flex: 1; 
            }
    
            .product { 
                border: 1px solid #ddd; 
                padding: 10px ; 
                border-radius: 5px; 
                text-align: center; 
            }
    
            .product img { 
                width: 100%; 
                border-radius: 5px;
                height: 300px; 
                object-fit: cover; 
 
            }
    
            .product h2 { 
                margin: 10px 0; 
                font-size: 18px; 
            }

    
            .product p { 
                font-size: 16px; 
                font-weight: bold; 
            }

            .pagination { 
                margin-bottom: 0px; 
                display: flex; 
                justify-content: center; 
                gap: 10px; 
            }

            .pagination a { 
                text-decoration: none; 
                padding: 5px 10px; 
                border: 1px solid #333; 
                color: #333; 
            }

            .pagination a.active { 
                background-color: #333; 
                color: white; 
            }

            .search-container {
                display: flex; /* Align input and button in the same row */
                gap: 10px; /* Space between input and button */
                margin:20px 0;
            }
            
            .search-container input {
                padding: 8px;
                width: 150px;
                font-size: 16px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
            
            .search-container button {
                padding: 8px 12px;
                font-size: 16px;
                background-color: #28a745;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            
            .search-container button:hover {
                background-color: #218838;
            }

            .product-link {
            text-decoration: none;
            color: inherit;
            }

            .image-container {
                position: relative;
                overflow: hidden;
                border-radius: 5px;
            }

            .image-container img {
                width: 100%;
                display: block;
                transition: opacity 0.3s ease;
            }

            .image-container .overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                opacity: 0;
                background: rgba(0, 0, 0, 0.4);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                font-weight: bold;
                transition: opacity 0.3s ease;
                pointer-events: none;
            }

            .image-container:hover img {
                opacity: 0.6;
            }

            .image-container:hover .overlay {
                opacity: 1;
            }

            .cart-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: #fff4fa;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }

        .cart-icon img {
            width: 40px;
            height: 40px;
        }

        .cart-icon:hover {
            transform: scale(1.1);
            background: #ffdae0;
        }

        </style>
    </head>
    <body>
        <div class="content">
            <div class="sidebar">
                <h3>Categories</h3>
                <form method="GET" class="search-container">
                    <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                    <button type="submit">Search</button>
                </form>
                <ul>
                    <?php foreach ($categories as $category) : ?>
                        <li><a href="?category=<?php echo urlencode($category); ?>"><?php echo htmlspecialchars($category); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="product-container">
                <?php foreach ($displayProducts as $product) : ?>
                    <div class="product">
                        <a href="product_view.php?id=<?php echo $product['productID']; ?>" class="product-link">
                        <div class="image-container">
                            <img src="image/<?php echo htmlspecialchars($product['image']) . '.png'; ?>" alt="<?php echo htmlspecialchars($product['productName']); ?>">
                            <div class="overlay">View Product</div>
                        </div>
                        </a>
                        <h2><?php echo htmlspecialchars($product['productName']); ?></h2>
                        <p>RM<?php echo number_format($product['price'], 2); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a href="?category=<?php echo urlencode($selectedCategory); ?>&search=<?php echo urlencode($searchQuery); ?>&page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"> <?php echo $i; ?> </a>
            <?php endfor; ?>
        </div> 
        
        <div class="cart-icon">
        <a href="cart.php">
            <img src="image/cart.png" alt="Cart">
        </a>
        </div>
        
    </body>
</html>

<?php include 'footer.php'; ?>