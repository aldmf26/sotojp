<a href="../../"><img src="<?= base_url('asset/'); ?>orchard_small2.png" width="200"></a>
<div class="login-box" style="font-size: 18px;">
  <div class="login-logo panel-default">
    <a href=""><b>Login Orchar</b></a>
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <?= $this->session->flashdata('message'); ?>
      <form action="<?= base_url('Login'); ?>" method="post">
        <div class="input-group mb-3 form-line">
          <input style="border:none; border-bottom: solid;" type="text" class="form-control" name="username" placeholder="Username" value="<?= set_value('username'); ?>">
          <span class="pt-3 fas fa-user"></span>
        </div>
        <?= form_error('username', '<small class="text-danger pl-3">', '</small>'); ?>
        <div class="input-group mb-3 form-line">
          <input style="border:none; border-bottom: solid;" type="password" class="form-control" name="password" placeholder="Password">
          <span class="pt-3 fas fa-lock"></span>
        </div>
        <?= form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
        <div class="row form-group last">
          <button type="submit" class="btn btn-block" style="background-color:#FADADD;">Login</button>
          <br>
        </div>
      </form>
      <hr>
      <!-- <a href="<?= base_url('Login/regis'); ?>"><u>Bikin Akun !</u></a> -->
    </div>
  </div>
</div>