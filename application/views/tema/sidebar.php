<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-pink elevation-4">
  <!-- Brand Logo -->
  <a href="" class="brand-link" style=" background:#FFF192; color: #787878;">
    <img src="<?= base_url('asset/img/');  ?>crepe_logo.png" alt="Orchard Beauty Product" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">CREPE</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <?php
        $menu = $this->db->get('tb_menu')->result();
        $sub_menu = $this->db->order_by('id_sub_menu', 'ASC')->get('tb_sub_menu')->result();
        ?>
        <?php foreach ($menu as $mn) : ?>
          <?php if (in_array($mn->id_menu, $this->session->userdata('dt_menu'))) : ?>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon <?= $mn->icon; ?>"></i>
                <p>
                  <?= $mn->menu; ?>
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <?php foreach ($sub_menu as $smn) : ?>
                  <?php if ($mn->id_menu == $smn->id_menu) : ?>
                    <?php if (in_array($smn->id_sub_menu, $this->session->userdata('permission'))) : ?>
                      <li class="nav-item">
                        <a href="<?= base_url($smn->url); ?>" class="nav-link">
                          <p><?= $smn->sub_menu; ?></p>
                        </a>
                      </li>
                    <?php endif; ?>
                  <?php endif; ?>

                <?php endforeach; ?>

              </ul>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>





      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">