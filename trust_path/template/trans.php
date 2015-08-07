<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="3;URL=<?php echo $url ?>"> 
<title><?php e(t(DURA_TITLE)) ?> | <?php e(t(DURA_SUBTITLE)) ?></title>
<link href="<?php echo DURA_URL; ?>/css/style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="body">
<div class="header">
<p><?php echo $message ?></p>
<p><?php e(t('If auto reload doesn\'t work,  please click <a href="{1}">here</a>.', $url)) ?></p>
</div>
</div>
</body>
</html>