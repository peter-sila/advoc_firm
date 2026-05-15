<?php
require_once __DIR__ . '/php/admin_auth.php';
require_admin();
require_once __DIR__ . '/php/db_config.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_blog') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $image = trim($_POST['image'] ?? '');

    if ($title === '' || $content === '') {
        $message = 'Please provide both a blog title and content.';
    } else {
        $stmt = $conn->prepare('INSERT INTO blogs (title, content, image, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->bind_param('sss', $title, $content, $image);
        if ($stmt->execute()) {
            $message = 'New blog post created successfully.';
        } else {
            $message = 'Unable to save the blog post. Please try again.';
        }
        $stmt->close();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete_blog' && isset($_GET['id'])) {
    $deleteId = (int) $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM blogs WHERE id = ?');
    $stmt->bind_param('i', $deleteId);
    if ($stmt->execute()) {
        $message = 'Blog post deleted successfully.';
    } else {
        $message = 'Unable to delete the selected blog post.';
    }
    $stmt->close();
}

$blogCount = $conn->query('SELECT COUNT(*) as total FROM blogs')->fetch_assoc()['total'] ?? 0;
$appointmentCount = $conn->query('SELECT COUNT(*) as total FROM appointments')->fetch_assoc()['total'] ?? 0;
$contactCount = $conn->query('SELECT COUNT(*) as total FROM contacts')->fetch_assoc()['total'] ?? 0;

$latestBlogs = $conn->query('SELECT id, title, created_at FROM blogs ORDER BY created_at DESC LIMIT 6')->fetch_all(MYSQLI_ASSOC);
$latestAppointments = $conn->query('SELECT id, name, expertise, appointment_date FROM appointments ORDER BY created_at DESC LIMIT 6')->fetch_all(MYSQLI_ASSOC);
$latestContacts = $conn->query('SELECT id, name, email, subject, created_at FROM contacts ORDER BY created_at DESC LIMIT 6')->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - WE GOT YOU Advocates</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="stat/css/headers.css">
  <link rel="stylesheet" href="stat/css/footers.css">
</head>
<body>
<?php include __DIR__ . '/stat/header.html'; ?>

<main class="admin-dashboard">
  <section class="page-header">
    <div>
      <p class="badge">Admin Panel</p>
      <h1>Website Management Dashboard</h1>
      <p>Review incoming messages, appointment requests, and publish new blog posts from a single interface.</p>
    </div>
    <div class="quick-links">
      <a href="admin.php" class="button button-secondary"><i class="fas fa-chart-line"></i> Refresh</a>
      <a href="appointments.php" class="button"><i class="fas fa-calendar-check"></i> Appointment Page</a>
      <a href="blogs.php" class="button button-secondary"><i class="fas fa-newspaper"></i> Blog Page</a>
      <a href="admin_logout.php" class="button"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </section>

  <?php if ($message): ?>
  <div class="admin-alert"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>

  <section class="summary-cards">
    <article class="card">
      <h2><?php echo (int) $blogCount; ?></h2>
      <p>Blog Posts</p>
    </article>
    <article class="card">
      <h2><?php echo (int) $appointmentCount; ?></h2>
      <p>Appointment Requests</p>
    </article>
    <article class="card">
      <h2><?php echo (int) $contactCount; ?></h2>
      <p>Contact Inquiries</p>
    </article>
  </section>

  <section class="panel-grid">
    <section class="panel panel-large">
      <div class="panel-heading">
        <h2>Latest Blog Posts</h2>
        <a href="#add-blog" class="button button-secondary">Add New Post</a>
      </div>
      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Published</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($latestBlogs as $blog): ?>
          <tr>
            <td><?php echo htmlspecialchars($blog['title']); ?></td>
            <td><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></td>
            <td><a class="link-button" href="admin.php?action=delete_blog&id=<?php echo (int) $blog['id']; ?>">Delete</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <section class="panel">
      <h2>Recent Appointment Requests</h2>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Expertise</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($latestAppointments as $appointment): ?>
          <tr>
            <td><?php echo htmlspecialchars($appointment['name']); ?></td>
            <td><?php echo htmlspecialchars($appointment['expertise']); ?></td>
            <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <section class="panel">
      <h2>Recent Contact Inquiries</h2>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($latestContacts as $contact): ?>
          <tr>
            <td><?php echo htmlspecialchars($contact['name']); ?></td>
            <td><?php echo htmlspecialchars($contact['email']); ?></td>
            <td><?php echo htmlspecialchars($contact['subject']); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </section>

  <section id="add-blog" class="panel">
    <h2>Publish a New Blog Post</h2>
    <form method="POST" class="admin-form">
      <input type="hidden" name="action" value="add_blog">
      <label>Blog Title</label>
      <input type="text" name="title" placeholder="Enter blog title" required>

      <label>Featured Image URL</label>
      <input type="url" name="image" placeholder="https://example.com/image.jpg">

      <label>Blog Content</label>
      <textarea name="content" rows="8" placeholder="Write the blog content here" required></textarea>

      <button type="submit" class="button">Publish Blog Post</button>
    </form>
  </section>
</main>

<?php include __DIR__ . '/stat/footer.html'; ?>
</body>
</html>

