<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/** @var string $trnId */
/** @var string $trnError */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Failed transaction</title>
</head>
<body>

<div id="container">
	<h1>Failed transaction #<?= $trnId ?></h1>
	<h4>Error: <?= $trnError ?></h4>
	<a href="/">To main</a>
</div>

</body>
</html>
