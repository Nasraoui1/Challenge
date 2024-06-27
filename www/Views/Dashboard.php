<!-- ../Views/dashboard.php -->

<h1>Dashboard</h1>

<div>
    <h2>User Statistics</h2>
    <p>Total Users: <?= $totalUsers ?></p>
    <p>Verified Users: <?= $verifiedUsers ?></p>
    <h3>Recent Users:</h3>
    <ul>
        <?php foreach ($recentUsers as $user): ?>
            <li><?= htmlspecialchars($user['username']) ?> - <?= htmlspecialchars($user['email']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div>
    <h2>Article Statistics</h2>
    <p>Total Articles: <?= $totalArticles ?></p>
    <p>Published Articles: <?= $publishedArticles ?></p>
    <p>Draft Articles: <?= $draftArticles ?></p>
    <h3>Recent Articles:</h3>
    <ul>
        <?php foreach ($recentArticles as $article): ?>
            <li><?= htmlspecialchars($article['title']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div>
    <h2>Comment Statistics</h2>
    <p>Total Comments: <?= $totalComments ?></p>
    <h3>Recent Comments:</h3>
    <ul>
        <?php foreach ($recentComments as $comment): ?>
            <li><?= htmlspecialchars($comment['content']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div>
    <h2>Category and Tag Statistics</h2>
    <p>Total Categories: <?= $totalCategories ?></p>
    <p>Total Tags: <?= $totalTags ?></p>
</div>

<div>
    <h2>Media Statistics</h2>
    <p>Total Media Files: <?= $totalMedia ?></p>
    <h3>Recent Media:</h3>
    <ul>
        <?php foreach ($recentMedia as $media): ?>
            <li><?= htmlspecialchars($media['file_name']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div>
    <h2>Recent Activity</h2>
    <ul>
        <?php foreach ($recentActivities as $activity): ?>
            <li><?= htmlspecialchars($activity['action']) ?> - <?= htmlspecialchars($activity['action_time']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
