<script language="JavaScript">
<!--
function autoResize(frame){
    var newheight;

    newheight=frame.contentWindow.document.body.scrollHeight;

    frame.height= (newheight) + "px";
}
//-->
</script>
<iframe src="<?php echo PATH_TO_ROOT; ?>/includes/phpinfo.inc.php" frameborder="0" width="100%" onLoad="autoResize(this)"></iframe>