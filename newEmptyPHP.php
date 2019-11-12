<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="topnav">
    <a class="active" href="#home">Home</a>
    <a href="#about">About</a>
    <a href="#contact">Contact</a>
    <div class="search-container">
        <form action="/action_page.php">
            <input type="text" placeholder="Search.." name="search">
            <button type="submit"><i class="fa fa-user-circle"></i></button>
        </form>
    </div>
</div>

<ul class="listing">
    
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use PDO;
$host = "proj-mysql.uopnet.plymouth.ac.uk";
$db = "ISAD251_SLippett";
$dsn = "mysql:host=$host;dbname=$db";
$conn = new PDO($dsn, 'ISAD251_SLippett', 'ISAD251_22214241');
$getPlayers = $conn->query("SELECT * FROM Product");
foreach ($getPlayers as $player) {
    echo '<li class="li">';
    echo '   <img class="img" src="'.$player[5].'"><img>';
    echo '    <h2>'.$player[1].'</h2>';
    echo '    <div class="body"><p>'.$player[2].'</p></div>';
    echo '    <div class="btn">Call to action!</div>';
    echo '</li>';
}

?>
</ul>


<style>
    
    .listing li {
    border: 5px;
    border-radius: 5px;
    display: flex;
    flex-direction: column;
}



.listing .cta {
    margin-top: auto;
    border-top: 1px solid #d49f37;
    padding: 10px;
    text-align: center;
}

.listing .body{
    padding: 10px;
    height: 100%;
}


.listing {
    list-style: none;
    margin: 2em;
    display: grid;
    grid-gap: 20px;
    grid-auto-flow: dense;
    grid-template-columns: repeat(auto-fill,minmax(200px, 1fr));
    cursor: default;
}

.listing .wide {
    grid-column-end: span 2;
}

html, body {
    height: 100%;
    width: 100%;
    margin: 0;
    font-family: 'Roboto', sans-serif;
    text-align:center
}

.btn {
    display: list-item;
    background-color: #d49f37;
    border-radius: 3px;
    font-size: 16px;
    color: #FFFFFF;
    text-decoration: none;
    transition: all .5s;
    cursor: pointer;
}
.btn:hover {
    background-color: #ffe060;
}


.li{
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    transition: 0.3s;
}

.li:hover
{
    box-shadow: 0 8px 20px 0 rgba(0,0,0,0.2);
}

.listing .img{
    object-fit :cover;
    height:160px;
    width:100%;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 2px;

}

.topnav {
    overflow: hidden;
    background-color: #e9e9e9;
    border-bottom-right-radius: 10px;
    border-bottom-left-radius: 10px;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);

}

.topnav:hover
{
    box-shadow: 0 8px 20px 0 rgba(0,0,0,0.2);
}

.topnav a {
    float: left;
    display: inline-block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 17px;
    border-radius:5px;
    margin-top: 10px;
    padding-left: 30px;
    padding-right: 30px;

}

.topnav a:hover {
    background-color: #ddd;
    color: black;
}

.topnav a.active {

    color: black;
}

.topnav .search-container {
    float: right;

}

.topnav input[type=text] {
    padding: 10px;
    margin: 12px;
    font-size: 17px;
    border: none;
    border-radius: 10px;
    margin-right: 5px


}

.topnav .search-container button {
    float: right;
    padding: 12px 12px;
    margin-top: 12px;
    margin-right: 56px;
    background: white;
    font-size: 17px;
    border: none;
    cursor: pointer;
    border-radius: 10px;

}

.topnav .search-container button:hover {
    background: #75db5d;
    border-radius:5px;
}

@media screen and (max-width: 600px) {
    .topnav .search-container {
        float: none;
    }
    .topnav a, .topnav input[type=text], .topnav .search-container button {
        float: none;
        display: block;
        text-align: left;
        width: 100%;
        margin: 0;
        padding: 14px;

    }
    .topnav input[type=text] {
        border: 1px solid #ccc;

    }
}
</style>