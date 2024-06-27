<!-- ../Views/Templates/base.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <header>
        <h1>My Application</h1>
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/dashboard">Dashboard</a></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php include "../Views/" . $this->view . ".php"; ?>
    </main>
    <footer>
        <p>&copy; 2024 My Application</p>
    </footer>
</body>
</html>
