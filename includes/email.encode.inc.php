<?php
// Librairie de fonctions PHP pour le brouyage des adresses

/**
  * Codage et affichage de tout texte
  *
  */
function codage($html)
{
      $html = chunk_split(bin2hex($html), 2, '%');
      $html = '%'. substr($html, 0, strlen($html)-1);
      $html = chunk_split($html, 54, "'+\n'");
      $html = substr($html, 0, strlen($html)-4);
      $html = "<script type=\"text/javascript\" language=\" javascript\"> \n<!--
      document.write(unescape( \n'$html')); //--> \n</script><noscript>
            <p><i>".VAR_LANG_NO_JAVA_SCRIPT_MAIL."</i></p>
            </noscript>";
      echo  $html;
}

/**
  * Codage et affichage d'une adresse email
  *
  */
function email($email)
{
    if (preg_match('/([^?]+)([?].*)/i', $email, $temp)) {
        $email = $temp[1];
        $tout = AddSlashes($temp[2]);
    }
    $email = preg_replace('|@|', "'+'@'+'", $email);
    $email = "var e='$email';" ;
    if ($tout) {
        $email .= "var f='$tout';" ;
    }
    $email .= "document.write('<a class=\"email sideIcon\" href=\"mailto:'+e";
    if ($tout) {
        $email .= "+f";
    }
    $email.= "+'\" title=\"'+e+'\">'+e+'</a>')";
    $email = chunk_split(bin2hex($email), 2, '%');
    $email ='%'. substr($email, 0, strlen($email)-1);
    $email = chunk_split($email, 54, "'+\n'");
    $email = substr($email, 0, strlen($email)-4);
    $email = "<script type=\"text/javascript\" language=\" javascript\"> \n<!--
    eval(unescape( \n'$email')); //-->\n </script><noscript>
          <p><i>".VAR_LANG_NO_JAVA_SCRIPT_MAIL."</i></p>
          </noscript>";
    echo  $email;
}

/**
  * Codage et affichage d'une adresse email avec un texte perso et un sujet
  *
  */
function emailperso($email, $texte, $sujet)
{
    if (preg_match('/([^?]+)([?].*)/i', $email, $temp)) {
        $email = $temp[1];
        $tout = AddSlashes($temp[2]);
    }
    $email = preg_replace('|@|', "'+'@'+'", $email);
    $email = "var e='$email';" ;
    if ($tout) {
        $email .= "var f='$tout';";
    }
    $email .= "document.write('<a class=\"email sideIcon\" href=\"mailto:'+e";
    if ($tout) {
        $email .= "+f";
    }
    $email .= "+'?subject=".$sujet."\" title=\"'+e+'\">".$texte."</a>')";
    $email = chunk_split(bin2hex($email), 2, '%');
    $email ='%'. substr($email, 0, strlen($email)-1);
    $email = chunk_split($email, 54, "'+\n'");
    $email = substr($email, 0, strlen($email)-4);
    $email = "<script type=\"text/javascript\" language=\" javascript\"> \n<!--
    eval(unescape( \n'$email')); //-->\n </script><noscript>
          <p><i>".VAR_LANG_NO_JAVA_SCRIPT_MAIL."</i></p>
          </noscript>";
    echo  $email;
}
