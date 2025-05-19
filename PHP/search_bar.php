<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search engine</title>
    </head>
    <body>
        <!-- Search bar (I-change lang ang css cha) -->
        <form method="GET" action="" style="margin-bottom: 20px;">
            <input type="text" name="search" placeholder="Search Student Number, surname, first name or status"
                value="<?php echo htmlspecialchars($search); ?>" style="padding: 8px; width: 300px; border: none; border-radius: 5px;">
            <button type="submit" style="padding: 8px 12px; border: none; border-radius: 5px;">Search</button>
        </form>
    </body>
</html>