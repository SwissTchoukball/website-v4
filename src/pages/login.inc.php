<div class="login">
    <?
    if (isset($_GET['error'])) {
	    switch ($_GET['error']) {
		    case 1:
		    	$messageErreurLogin = "Vous n'�tes pas dans la base de donn�es.";
				break;
			case 2:
				$messageErreurLogin = "mot de passe incorrect";
				break;
			case 3:
				$messageErreurLogin = "Erreur de s�curit�.";
				break;
			case 4:
				$messageErreurLogin = "Erreur SQL.";
				break;
			default:
				$messageErreurLogin = "Erreur inconnue.";
	    }

	    echo '<p class="error">'.$messageErreurLogin.'</p>';
    }

	printMessage("Un nom d'utilisateur doit d�sormais �tre indiqu� en lieu et place de votre nom et pr�nom.<br />".
					 "Le nom d'utilisateur est compos� de la premi�re lettre de votre pr�nom et de votre nom en entier.<br />".
					 "Exemple : <em>John Doe</em> devient <em>jdoe</em>.");
    ?>
    <form name="log" method="post" action="gestionLogin.php">
        <table border="0" align="center">
            <tr>
                <td width="120px"><p><? echo VAR_LANG_USERNAME;?> :</p></td>
                <td><input name="username" type="text" size="35" maxlength="35"></td>
            </tr>
            <tr>
                <td><p><? echo VAR_LANG_PASSWORD;?> :</p></td>
                <td><input name="password" type="password" size="35" maxlength="35"></td>
            </tr>
            <tr>
                <td colspan="2"><p align="center"><? echo VAR_LANG_AUTO_CONNECTION;?>
                    <input class="couleurCheckBox" type="checkbox" name="autoConnect">
                </td>
            </tr>
            <tr>
                <td colspan="2"><div align="center">
                    <input type="submit" name="login" value="<? echo VAR_LANG_SE_LOGUER;?>">
                </td>
            </tr>
        </table>
    </form>
</div>