<div class="header">
<form action="<?php echo $dura['action'] ?>" method="post" id="message">
<div class="right">
<input type="submit" name="logout" value="LOGOUT" />
</div>
<textarea name="message"></textarea>
<div class="submit">
<span class="button"><input type="submit" value="POST!" id="post" /></span>
</div>
<span id="time" class="hide"><?php echo time() ?></span>
<?php if ( $ret = file_exists(DURA_PATH.'/js/sound.mp3') ) : ?>
<a href="<?php echo DURA_URL ?>/js/sound.mp3" id="sound" class="hide">sound</a>
<?php endif ?>
<ul class="user_names hide">
<?php foreach ( $dura['users'] as $user  ) : ?>
<li><?php echo $user ?></li>
<?php endforeach ?>
</ul>
</form>

<?php /*
<ul class="user_names">
<?php foreach ( $dura['users'] as $user ) : ?>
<li><?php echo $user ?></li>
<?php endforeach ?>
</ul>
*/ ?>

</div><!-- end #header -->

<div id="chat">
<?php foreach ( $dura['logs'] as $log ) : ?>
<?php if ( !$log['id'] ) : ?>
<div class="system discuss" id="<?php echo $log['hash'] ?>">
ーー <?php echo $log['message'] ?>
</div>
<?php else: ?>
<dl class="discuss <?php echo $log['icon'] ?>" id="<?php echo $log['hash'] ?>">
<dt><?php echo $log['name'] ?></dt>
<dd>
	<div class="bubble">
		<p class="body"><?php echo $log['message'] ?></p>
	</div>
</dd>
</dl>
<?php endif ?>
<?php endforeach ?>
</div><!-- /#logs -->
