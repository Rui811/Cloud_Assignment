<?php include 'header.php'; ?>
<?php
$categories = ["All", "Roses", "Sunflowers", "Lilies", "Mixed Bouquets"];
$products = [
    ["id" => 1, "name" => "Rosie Red Mini", "price" => 49.90, "image" => "image/flower1.png", "category" => "Roses"],
    ["id" => 2, "name" => "Sunflower 1 Stalk", "price" => 48.90, "image" => "sunflower_1_stalk.jpg", "category" => "Sunflowers"],
    ["id" => 3, "name" => "Blushing Bride", "price" => 99.00, "image" => "blushing_bride.jpg", "category" => "Mixed Bouquets"],
    ["id" => 4, "name" => "Iris Mini", "price" => 79.90, "image" => "iris_mini.jpg", "category" => "Lilies"],
    ["id" => 5, "name" => "Rosie Red Mini", "price" => 49.90, "image" => "image/flower1.png", "category" => "Roses"],
    ["id" => 6, "name" => "Sunflower 1 Stalk", "price" => 48.90, "image" => "sunflower_1_stalk.jpg", "category" => "Sunflowers"],
    ["id" => 7, "name" => "Blushing Bride", "price" => 99.00, "image" => "blushing_bride.jpg", "category" => "Mixed Bouquets"],
    ["id" => 8, "name" => "Iris Mini", "price" => 79.90, "image" => "iris_mini.jpg", "category" => "Lilies"]
];

$selectedCategory = $_GET['category'] ?? "All";
$searchQuery = $_GET['search'] ?? "";
$filteredProducts = array_filter($products, function($product) use ($selectedCategory, $searchQuery) {
    return ($selectedCategory === "All" || $product['category'] === $selectedCategory) &&
           (empty($searchQuery) || stripos($product['name'], $searchQuery) !== false);
});

$perPage = 15; // 12 products per page
$totalProducts = count($filteredProducts);
$totalPages = ceil($totalProducts / $perPage);
$page = isset($_GET['page']) ? max(1, min($_GET['page'], $totalPages)) : 1;
$startIndex = ($page - 1) * $perPage;
$displayProducts = array_slice($filteredProducts, $startIndex, $perPage);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graduation Bouquets</title>
    <style>
        body { font-family: Arial, sans-serif; margin: auto; padding: 0;overflow-x: hidden;  display: flex; flex-direction: column;  min-height: 100vh; }
header, footer { width: 100%; background-color: #333; color: white; text-align: center; padding: 15px 0; position: fixed; }
header { top: 0; }
footer { bottom: 0; }
.content { overflow-y: auto;
    overflow-x: auto;flex: 1;margin-top: 80px; margin-bottom: 80px;margin-left:100px;margin-right:100px; padding: 0; display: flex;  gap: 10px; }
.sidebar { width: 250px; padding: 10px; background: #f8f8f8; }
.sidebar ul { list-style-type: none; padding: 0; } /* Remove dots from the category list */
.sidebar li { margin-bottom: 10px; } /* Optional: adds space between the categories */
.sidebar a {
    font-size: 18px; /* Increase font size */
    
    color: #333; /* Default text color */
    padding-bottom: 2px; /* Add some space between text and the dotted line */
}
.sidebar a:hover {
    color: #28a745; /* Change text color on hover */
    border-color: #28a745; /* Change the underline color on hover */
    font-weight: bold;
    
}
.product-container { width: 100%;display: grid;margin-left:30px; grid-template-columns: repeat(4, 1fr); gap: 30px; flex: 1; }
.product { border: 1px solid #ddd; padding: 10px ; border-radius: 5px; text-align: center; }
.product img { width: 100%; height: auto; border-radius: 5px; }
.product h2 { margin: 10px 0; font-size: 18px; }
.product p { font-size: 16px; font-weight: bold; }
.add-to-cart { background-color: #28a745; color: white; padding: 10px; border: none; cursor: pointer; }
.add-to-cart:hover { background-color: #218838; }
.pagination { margin-top: 20px; display: flex; justify-content: center; gap: 10px; }
.pagination a { text-decoration: none; padding: 5px 10px; border: 1px solid #333; color: #333; }
.pagination a.active { background-color: #333; color: white; }
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
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p>RM<?php echo number_format($product['price'], 2); ?> MYR</p>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
            <a href="?category=<?php echo urlencode($selectedCategory); ?>&search=<?php echo urlencode($searchQuery); ?>&page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"> <?php echo $i; ?> </a>
        <?php endfor; ?>
    </div>
    
    
</body>
</html>

<?php include 'footer.php'; ?>