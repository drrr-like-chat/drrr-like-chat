<ul id="profile">
<li class="icon"><img src="<?php echo $dura['profile']['icon'] ?>" /></li>
<li class="name"><?php echo $dura['profile']['name'] ?></li>
<li class="logout">
<form action="<?php echo Dura::url('logout') ?>" method="post">
<input type="submit" class="input" value="LOGOUT" />
</form>
</li>
</ul>

<div class="clear"></div>


<div class="header">
<h2>Rooms</h2>

<?php /*
<div id="create_room">
<form action="<?php echo Dura::url('logout') ?>" method="post">
<span href="#" class="button"><input type="submit" class="input" value="LOGOUT" /></span>
</form>
</div>
*/ ?>

<div class="clear"></div>

<?php foreach ( $dura['rooms'] as $room ) : ?>
<ul class="rooms">
<li class="name"><?php echo $room['name'] ?></li>
<?php /*
<li class="creater"><?php echo $room['creater'] ?></li>
*/ ?>
<li class="member"><?php echo $room['total'] ?> / <?php echo $room['limit'] ?></li>
<li class="login">
<?php if ( $room['total'] >= $room['limit'] ) : ?>
満室
<?php else : ?>
<form action="<?php echo $room['url'] ?>" method="post">
<span class="button">
<input type="submit" name="login" value="LOGIN" class="input" />
</span>
</form>
<?php endif ?>
</li>
</ul>
<?php endforeach ?>

<div class="clear"></div>

</div>