<?php 
if(!isset($_SESSION))
{
      session_start();
   $_SESSION['CART'] = array();
}
include_once 'navbar.php';

?>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="../assets/css/homepage.css"/>
</head>

<body>

<div class="bgimg-1">
    <div class="caption">
        <span class="border">WELCOME</span><br>
        <span class="border">HOME</span>
    </div>
</div>

</body>