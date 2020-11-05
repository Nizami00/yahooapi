<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Yahoo Api</title>
</head>
<body>
<form action="/" method="post">
    <label for="search">Enter stock</label>
    <input type="text" id="search" name="search" required/>
    <button type="submit">Search</button>
    <?php if (isset($stock)) : ?>
        <p>Stock You searched for</p>
        <ul>
            <li><?php echo $stock->getShortName() ?></li>
            <br>
            <li><?php echo $stock->getLongName() ?></li>
            <br>
            <li><?php echo $stock->getPreviousClose() ?></li>
            <br>
            <li><?php echo $stock->getOpen() ?></li>
            <br>
            <li><?php echo number_format($stock->getVolume()) ?></li>
            <br>
            <li><?php echo number_format($stock->getAvgVolume()) ?></li>
            <br>
            <li><?php echo $stock->getTime() ?></li>
            <br>
        </ul>
    <?php endif; ?>
</form>
</body>
</html>