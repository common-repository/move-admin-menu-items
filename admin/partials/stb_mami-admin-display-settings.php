<form action='options.php' method='post'>
  <?php
  settings_fields('stb_mami');
  do_settings_sections('stb_mami');
  submit_button();
  ?>
</form>
