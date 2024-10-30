<?php $admin_menu = $this->stb_mami_create_admin_menu(); ?>

<h1><?php _e('Admin Menu', $this->plugin_name); ?></h1>
<div class="stb_mami_container">
  <?php if ($admin_menu) : ?>
  <?php foreach ($admin_menu as $menu) : ?>
  <?php if (!empty($menu)) : ?>
  <div class="stb_mami_container__item">
    <h2>
      <a href="<?php echo $menu['menu_url']; ?>">
        <?php echo $menu['menu_icon']; ?>
        <?php echo $menu['menu_name']; ?>
      </a>
    </h2>
    <?php if ($menu['menu_submenu']) : ?>
    <ul>
      <?php foreach ($menu['menu_submenu'] as $submenu) : ?>
      <li>
        <a href="<?php echo $submenu['submenu_url']; ?>">
          <?php echo $submenu['submenu_name']; ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
  <?php endif; ?>
  <?php endforeach; ?>
  <?php else : ?>
  <p>
    <?php echo sprintf(
        __('Go to the <a href="%s">settings-page</a> to select which menu items you want to move here.', $this->plugin_name),
        esc_url(
          menu_page_url('stb_mami_settings', false)
        )
      ); ?>
  </p>
  <?php endif; ?>
</div>
