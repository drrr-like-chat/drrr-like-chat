<div class="message_box">
<div class="message_box_inner">
<form action="#" method="post" id="message">
<div class="right">
<input type="submit" name="logout" value="LOGOUT" />
</div>
<h2><?php e($dura['room']['name']) ?></h2>
<textarea name="message"></textarea>
<?php if ( $ret = file_exists(DURA_PATH.'/js/sound.mp3') ) : ?>
<a href="<?php echo DURA_URL ?>/js/sound.mp3" id="sound" class="hide">sound</a>
<?php endif ?>
<div class="submit">
<span class="button"><input type="submit" name="post" value="POST!" /></span>
</div>
<ul id="members" class="hide">
<?php foreach ( $dura['room']['users'] as $user  ) : ?>
<li><?php e($user['name']) ?></li>
<?php endforeach ?>
</ul>
<ul class="hide">
<li id="user_id"><?php e($dura['user']['id']) ?></li>
<li id="user_name"><?php e($dura['user']['name']) ?></li>
<li id="user_icon"><?php e($dura['user']['icon']) ?></li>
</ul>

</form>
</div>
</div><!-- end #header -->

<div id="talks">
<?php foreach ( $dura['room']['talks'] as $talk ) : ?>
<?php if ( !$talk['uid'] ) : ?>
<div class="talk system" id="<?php e($talk['id']) ?>"><?php e($talk['message']) ?></div>
<?php else: ?>
<dl class="talk <?php e($talk['icon']) ?>" id="<?php e($talk['id']) ?>">
<dt><?php e($talk['name']) ?></dt>
<dd>
	<div class="bubble">
		<p class="body"><?php e($talk['message']) ?></p>
	</div>
</dd>
</dl>
<?php endif ?>
<?php endforeach ?>
</div><!-- /#logs -->
