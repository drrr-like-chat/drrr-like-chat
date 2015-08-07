<div id="login" class="header">
<?php if ( $dura['error'] ) : ?>
<div class="error">
<?php echo $dura['error'] ?>
</div>
<?php endif ?>
<form action="#" method="post">

<div class="language">
<select name="language">
<?php foreach ( $dura['languages'] as $langcode => $language ) : ?>
<option value="<?php e($langcode) ?>"<?php if ( $langcode == $dura['default_language']): ?> selected="selected"<?php endif ?>><?php e($language) ?></option>
<?php endforeach ?>
</select>
</div>

<ul class="icons">
<?php foreach ( $dura['icons'] as $icon => $file ) : ?>
<li>
<label>
<img src="<?php echo DURA_URL.'/css/'.$file ?>" />
<input type="radio" name="icon" value="<?php echo $icon ?>" />
</label>
</li>
<?php endforeach ?>
</ul>

<div class="field">
<input type="textbox" name="name" value="" size="10" maxlength="10" class="textbox" />
<span class="button">
<input type="submit" name="login" value="<?php e(t("ENTER")) ?>" />
</span>
</div>

<input type="hidden" name="token" value="<?php echo $dura['token'] ?>" />

</form>

<?php if ( file_exists(DURA_TEMPLATE_PATH.'/footer.html') ) : ?>
<div class="footer">
<?php require DURA_TEMPLATE_PATH.'/footer.html' ?>
</div>
<?php endif ?>
<div class="copyright">
<a href="<?php e(Dura::url('admin')) ?>"><?php e("Admin") ?></a> |
Durarara-like-chat Copyright (c) 2010 <a href="http://suin.asia/">Suin</a> | <a href="https://github.com/drrr-like-chat/drrr-like-chat">get this chat?</a></div>

</div>
