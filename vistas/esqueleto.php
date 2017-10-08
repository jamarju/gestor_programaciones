<!DOCTYPE html>
<html>
<head>
    <title><?=$this->e($title)?></title>
    <meta charset="UTF-8">
    <link href="css/estilos.css" rel="stylesheet" type="text/css">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="scripts/jquery-3.2.1.min.js" type="text/javascript"></script>
    <script src="scripts/scripts.js" type="text/javascript"></script>
</head>
<body>

<div id="header">
<?=$this->section('header') ?>
</div>

<div id="container">
<?=$this->section('content')?>
</div>

</body>
</html>
