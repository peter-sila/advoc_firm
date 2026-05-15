<?php
require_once __DIR__ . '/php/db_config.php';

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 4;

$result = $conn->query("SELECT id, title, content, image FROM blogs ORDER BY created_at DESC LIMIT $limit OFFSET $offset");

$blogs = [];
while ($row = $result->fetch_assoc()) {
  $blogs[] = $row;
}

if (isset($_GET['ajax'])) {
  header('Content-Type: application/json');
  echo json_encode($blogs);
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Latest Legal Insights - WE GOT YOU Advocates</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="stylesheet" href="css/global.css">
   <link rel="stylesheet" href="css/blogs.css">
   <link rel="stylesheet" href="stat/css/footers.css">
   <link rel="stylesheet" href="stat/css/headers.css">
</head>
<body>
   <?php include "stat/header.html"; ?>
   
   <section class="latest-news">
      <h2>Latest Legal Insights & Articles</h2>
      <div id="blog-container">
         <?php if(count($blogs) > 0): ?>
            <?php foreach($blogs as $row): ?>
               <div class="n-card">
                  <img src="<?php echo htmlspecialchars($row['image'] ?? 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=300'); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                  <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                  <p><?php echo htmlspecialchars(substr($row['content'], 0, 100)) . '...'; ?></p>
                  <a href="viewblog.php?id=<?php echo $row['id']; ?>">Read More</a>
               </div>
            <?php endforeach; ?>
         <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
               <p>No articles available at the moment. Check back soon!</p>
            </div>
         <?php endif; ?>
      </div>
      <button id="loadMoreBtn">Load More Articles</button>
   </section>
   
   <section class="market">
      <h3>Need Legal Guidance?</h3>
      <hr>
      <p>Let us help you! Call us now: <a href="tel:+254794178635">+254 794 178 635</a></p>
      <p><a href="mailto:petersila2002@gmail.com">petersila2002@gmail.com</a></p>
   </section>

   <?php include "stat/footer.html"; ?>
   <script src="js/blogs.js"></script>
</body>
</html>

