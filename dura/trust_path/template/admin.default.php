<div id="login" class="header">
<?php if ( $dura['error'] ) : ?>
<div class="error">
<?php echo $dura['error'] ?>
</div>
<?php endif ?>
<form action="#" method="post">

<div class="field">
<?php e(t("Admin ID")) ?><br />
<input type="textbox" name="name" value="" size="10" maxlength="10" class="textbox" /><br />
<?php e(t("Password")) ?><br />
<input type="password" name="pass" value="" size="10" class="textbox" />
<span class="button">
<input type="submit" name="login" value="<?php e(t("ENTER")) ?>" />
</span>
</div>

<input type="hidden" name="token" value="<?php echo $dura['token'] ?>" />

</form>

</div>