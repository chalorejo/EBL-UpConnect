<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search engine</title>
        <link rel="stylesheet" href="../CSS/search.css">
    </head>
    <body>
        <div class="search-container">
            <form method="GET" action="" style="margin-bottom: 20px;">
                <input type="text" name="search" placeholder="Search Student Number, surname, first name or status"
                    value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><b>Search</b></button>
            </form>
        </div>
    </body>
</html>