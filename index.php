<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles/index_style.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet" />
    <title>Story Frame</title>
</head>

<body>
    <header>
        <div class="logo">
            <img src="assets/logo.png" alt="logo-story-frame" />
            <p>Story Frame</p>
        </div>
        <nav>
            <?php
        $menuItems = ["Home", "About", "Portfolio", "Contact"];
        foreach ($menuItems as $item) {
            echo "<a href='#'>$item</a>";
        }
        ?>
        </nav>
        <div id="button">
            <button id="btn-primary">Sign In</button>
            <button id="btn-secondary">Sign Up</button>
        </div>
    </header>
    <section id="hero">
        <h1>visual poetry</h1>
        <p>
            Welcome to a visual journey that transcends time and space. Discover the
            artistry of moments captured in motion
        </p>
        <a href="form.php"><button>Booking
            </button>
        </a>
    </section>
</body>
<footer></footer>

</html>