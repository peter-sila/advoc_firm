<?php
$blog_id = $_GET['id'] ?? 0;
require_once __DIR__ . '/php/db_config.php';

// Fetch main blog post
$blog_result = $conn->query("SELECT * FROM blogs WHERE id = $blog_id");
if ($blog_result->num_rows == 0) {
  die("Blog post not found.");
}
$blog = $blog_result->fetch_assoc();

// Fetch related posts
$related_result = $conn->query("SELECT id, title, image FROM blogs WHERE id != $blog_id ORDER BY created_at DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($blog['title']); ?> - WE GOT YOU Advocates</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/viewblog.css">
  <link rel="stylesheet" href="stat/css/footers.css">
  <link rel="stylesheet" href="stat/css/headers.css">
</head>
<body>
<?php include "stat/header.html"; ?>

<div class="container clearfix">
  <div class="main-content">
    <img src="<?php echo htmlspecialchars($blog['image'] ?? 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=800'); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="header-image">
    
    <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
    <p class="meta">
      <?php echo date('F j, Y', strtotime($blog['created_at'] ?? 'now')); ?> | 
      <?php $words = str_word_count($blog['content']); $minutes = ceil($words / 200); echo $minutes . ' min read'; ?> |
      <a href="#comments">Comments</a>
    </p>

    <div class="content">
      <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
    </div>
  </div>

  <aside class="sidebar">
    <h3>Latest Legal Insights</h3>
    <ul class="insights">
      <li><a href="blogs.php?category=employment"><i class="fas fa-briefcase"></i> Employment Law</a></li>
      <li><a href="blogs.php?category=corporate"><i class="fas fa-building"></i> Corporate Law</a></li>
      <li><a href="blogs.php?category=real-estate"><i class="fas fa-home"></i> Real Estate Law</a></li>
      <li><a href="blogs.php?category=civil"><i class="fas fa-gavel"></i> Civil Litigation</a></li>
      <li><a href="blogs.php?category=criminal"><i class="fas fa-shield-alt"></i> Criminal Defense</a></li>
    </ul>

    <div class="search-box">
      <h4>Search Articles</h4>
      <input type="text" id="searchInput" placeholder="Search legal articles...">
    </div>

    <div class="tags">
      <h4>Tags</h4>
      <span onclick="searchTag(this)">Legal</span>
      <span onclick="searchTag(this)">Advice</span>
      <span onclick="searchTag(this)">Law</span>
      <span onclick="searchTag(this)">Kenya</span>
      <span onclick="searchTag(this)">Rights</span>
    </div>
  </aside>
</div>

<?php if($related_result->num_rows > 0): ?>
<section class="related-posts">
  <h3>Related Articles</h3>
  <div class="related-posts-grid">
    <?php while($related = $related_result->fetch_assoc()): ?>
    <div class="related-card">
      <img src="<?php echo htmlspecialchars($related['image'] ?? 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=300'); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>">
      <h4><?php echo htmlspecialchars($related['title']); ?></h4>
      <a href="viewblog.php?id=<?php echo $related['id']; ?>">Read Article</a>
    </div>
    <?php endwhile; ?>
  </div>
</section>
<?php endif; ?>

<?php include "stat/footer.html"; ?>
<script src="js/viewblog.js"></script>
</body>
</html>

  <div class="content">
    <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
    <p><em>Published on: <?php echo htmlspecialchars($blog['created_at']); ?></em></p>
    <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>

    <?php while ($heading = $headings_result->fetch_assoc()): ?>
      <div class="heading"><?php echo htmlspecialchars($heading['heading']); ?></div>
    <?php endwhile; ?>

    <?php while ($image = $images_result->fetch_assoc()): ?>
      <div class="image-box">
        <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="">
        <?php if ($image['caption']) echo "<p><em>" . htmlspecialchars($image['caption']) . "</em></p>"; ?>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="sidebar">
    <h3>Latest Legal Insights</h3>
    <ul>
      <li><a href="#">Labor & Employment</a></li>
      <li><a href="#">Constitutional Law</a></li>
      <li><a href="#">Data Protection</a></li>
    </ul>
  </div>

</div>
</body>
</html>

