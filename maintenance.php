
<?php
	// laisser passer l'administrateur
	if($_SESSION["__userLevel__"]!=0){
?>
		<html>
		<head>
            <title>Swiss Tchoukball</title>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
            <style>
                body {
                    font-family: Arial, Helvetica, sans-serif;
                    text-align: center;
                }
                h1, h2 {
                    color: #e2001a;
                    text-align: center;
                }
                p {
                    text-align: left;
                    width: 700px;
                    margin: auto;
                }
                .contenu {
                    text-align: left;
                }
                .image {
                    margin-left: 136px;
                }
            </style>
		</head>
		<div class="contenu">
		<h1>Swiss Tchoukball</h1>

		<p>
		Site en maintenance, merci de revenir plus tard.<br />
		<br />
		Website in maintenance, please come back later.
		</p>
		</div>
		</body>
		</html>
<?
		die;
	}

?>
