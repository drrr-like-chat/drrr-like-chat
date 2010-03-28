<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
<title>デュラララ！チャットルーム</title>
<link href="<?php echo DURA_URL; ?>/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="http://www.google.com/jsapi"></script> 
<script type="text/javascript"><!--
google.load("language", "1"); 
google.load("jquery", "1");
google.load("jqueryui", "1");
dura_url = "<?php echo DURA_URL ?>";
//-->
</script>
<?php if ( Dura::$controller == 'chat' ) : ?>
<script type="text/javascript" src="<?php echo DURA_URL; ?>/js/jquery.sound.js"></script>
<script type="text/javascript" src="<?php echo DURA_URL; ?>/js/jquery.chat.js"></script>
<?php endif ?>
<?php if ( file_exists(DURA_PATH.'/header.html') ) require(DURA_PATH.'/header.html'); ?>
</head>
<body>
<div id="body">
<?php echo $content; ?>
</div>
</body>
</html>