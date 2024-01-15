</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<footer class="main-footer shadow" style=" background:#FFF192;">
  <div class="float-right d-none d-sm-inline">
    Anything you want
  </div>
  <strong>Copyright &copy; 2024.01.08 <a href="<?= 'https:www.putrirembulan.com'; ?>" target="" style="color: #787878;">putrirembulan.com</a></strong>
</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="<?= base_url('asset/'); ?>/plugins/jquery/jquery.min.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap -->
<script src="<?= base_url('asset/adminlte/js/'); ?>bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url('asset/adminlte/js/'); ?>jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('asset/adminlte/js/'); ?>adminlte.js"></script>

<script src="<?= base_url('asset/'); ?>/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>


<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<!-- <script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="plugins/raphael/raphael.min.js"></script>
<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="plugins/jquery-mapael/maps/usa_states.min.js"></script> -->
<!-- ChartJS -->
<!-- <script src="plugins/chart.js/Chart.min.js"></script> -->

<!-- PAGE SCRIPTS -->
<script src="<?= base_url('asset/adminlte/js/'); ?>dashboard2.js"></script>
<script src="<?= base_url('asset/adminlte/js/'); ?>sweetalert2.min.js"></script>

<script src="<?= base_url('asset/'); ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
  // Gunakan jQuery untuk menoggle sidebar
  $(document).ready(function() {
    $("#sidebarToggle").click(function() {
      $("body").toggleClass("sidebar-collapse");
    });
  });
</script>
<script>
  $(document).ready(function() {








    $(document).on('click', '.plus_cart_app', function(event) {
      var rowid = $(this).attr("id");
      var qty = $(this).attr("qty");
      $.ajax({
        url: "<?= base_url(); ?>match/plus_cart_app/",
        method: "POST",
        data: {
          rowid: rowid,
          qty: qty
        },
        success: function(data) {
          load_cart();
        }
      });
    });


    $(document).on('click', '.min_kry', function(event) {
      var rowid = $(this).attr("rowid");
      var key = $(this).attr("key");
      $.ajax({
        url: "<?= base_url(); ?>match/min_kry/",
        method: "POST",
        data: {
          rowid: rowid,
          key: key
        },
        success: function(data) {
          // $('#cart_session').html(data); 
          load_cart();
        }
      });
    });

    $(document).on('click', '.diskon_servis', function(event) {
      var rowid = $(this).attr("id_cart");
      var price_cart = $(this).attr("price_cart");

      $('#id_cart').val(rowid);
      $('#price_cart').val(price_cart);
    });

    $(document).on('submit', '#diskon_servis', function(event) {
      event.preventDefault();
      $.ajax({
        url: "<?php echo base_url('match/tambah_diskon_cart'); ?>",
        method: 'POST',
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function(data) {
          $('#discon_servis').modal('hide');
          load_cart();
          // alert(data);
        }
      });
    });


  });






  $(document).ready(function() {
    $('#list_kategori').on('change', function() {
      var id = $('#list_kategori').val();
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url('Match/get_produk'); ?>',
        data: {
          'id': id
        },
        success: function(data) {
          $("#list_produk").html(data);
        }

      })
    })
  });



  // $(document).ready(function(){
  //   load_data();
  //   function load_data(kategori, keyword)
  //   {
  //     $.ajax({
  //       method:"POST",
  //       url:"<?php echo site_url() ?>match/search_produk",
  //       data: {kategori: kategori, keyword:keyword},
  //       success:function(hasil)
  //       {
  //         $('.data-produk').html(hasil);
  //       }
  //     });
  //   }
  //   $('#keyword').keyup(function(){
  //     var kategori = $("#kategori").val();
  //       var keyword = $("#keyword").val();
  //     load_data(kategori, keyword);
  //   });
  //   $('#kategori').change(function(){
  //     var kategori = $("#kategori").val();
  //       var keyword = $("#keyword").val();
  //     load_data(kategori, keyword);
  //   });
  // });

  $(window).resize(function() {
    if ($(window).width() <= 600) {
      $('#prop-type-group').removeClass('btn-group');
      $('#prop-type-group').addClass('btn-group-vertical');
    } else {
      $('#prop-type-group').addClass('btn-group');
      $('#prop-type-group').removeClass('btn-group-vertical');
    }
  });
</script>
<script>
  $(function() {
    $("input[name='picker']").daterangepicker({
      opens: 'center',
      drops: 'up'
    }, function(start, end, label) {

    });
    $('#picker').daterangepicker();
    $('#picker').on('apply.daterangepicker', function(ev, picker) {

      document.getElementById("tanggal1").value = picker.startDate.format('YYYY-MM-DD');
      document.getElementById("tanggal2").value = picker.endDate.format('YYYY-MM-DD');
    });
  });

  $(function() {
    $("input[name='picker_excel']").daterangepicker({
      opens: 'center',
      drops: 'up'
    }, function(start, end, label) {

    });
    $('#picker_excel').daterangepicker();
    $('#picker_excel').on('apply.daterangepicker', function(ev, picker) {

      document.getElementById("tgl1").value = picker.startDate.format('YYYY-MM-DD');
      document.getElementById("tgl2").value = picker.endDate.format('YYYY-MM-DD');
    });
  });

  $(function() {
    $("input[name='pickerkas']").daterangepicker({
      opens: 'center',
      drops: 'up'
    }, function(start, end, label) {

    });
    $('#pickerkas').daterangepicker();
    $('#pickerkas').on('apply.daterangepicker', function(ev, pickerkas) {

      document.getElementById("tanggalkas1").value = pickerkas.startDate.format('YYYY-MM-DD');
      document.getElementById("tanggalkas2").value = pickerkas.endDate.format('YYYY-MM-DD');
    });
  });

  $(function() {
    $("input[name='picker2']").daterangepicker({
      opens: 'center',
      drops: 'up'
    }, function(start, end, label) {

    });
    $('#picker2').daterangepicker();
    $('#picker2').on('apply.daterangepicker', function(ev, picker2) {

      document.getElementById("tanggal3").value = picker2.startDate.format('YYYY-MM-DD');
      document.getElementById("tanggal4").value = picker2.endDate.format('YYYY-MM-DD');
    });
  });

  $(function() {
    $("input[name='picker3']").daterangepicker({
      opens: 'center',
      drops: 'up'
    }, function(start, end, label) {

    });
    $('#picker3').daterangepicker();
    $('#picker3').on('apply.daterangepicker', function(ev, picker3) {

      document.getElementById("tanggal3").value = picker3.startDate.format('YYYY-MM-DD');
      document.getElementById("tanggal4").value = picker3.endDate.format('YYYY-MM-DD');
    });
  });

  $(function() {
    $("input[name='picker_drop']").daterangepicker({
      opens: 'center',
      drops: 'up'
    }, function(start, end, label) {

    });
    $('#picker_drop').daterangepicker();
    $('#picker_drop').on('apply.daterangepicker', function(ev, picker_drop) {

      document.getElementById("tanggal5").value = picker_drop.startDate.format('YYYY-MM-DD');
      document.getElementById("tanggal6").value = picker_drop.endDate.format('YYYY-MM-DD');
    });
  });

  $(function() {
    $("input[name='global']").daterangepicker({
      opens: 'up',
      drops: 'up'
    }, function(start, end, label) {

    });
    $('#global').daterangepicker();
    $('#global').on('apply.daterangepicker', function(ev, global) {

      document.getElementById("global1").value = global.startDate.format('YYYY-MM-DD');
      document.getElementById("global2").value = global.endDate.format('YYYY-MM-DD');
    });
  });

  $(function() {
    $("input[name='trash']").daterangepicker({
      opens: 'up',
      drops: 'up'
    }, function(start, end, label) {

    });
    $('#trash').daterangepicker();
    $('#trash').on('apply.daterangepicker', function(ev, trash) {

      document.getElementById("trash1").value = trash.startDate.format('YYYY-MM-DD');
      document.getElementById("trash2").value = trash.endDate.format('YYYY-MM-DD');
    });
  });

  $(function() {
    $("input[name='excel']").daterangepicker({
      opens: 'up',
      drops: 'up'
    }, function(start, end, label) {

    });
    $('#excel').daterangepicker();
    $('#excel').on('apply.daterangepicker', function(ev, excel) {

      document.getElementById("excel1").value = excel.startDate.format('YYYY-MM-DD');
      document.getElementById("excel2").value = excel.endDate.format('YYYY-MM-DD');
    });
  });


  $(function() {
    $('.select').select2()

    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });

  $(function() {
    $("#example1").DataTable({

      "responsive": true,
      "bSort": true,
      // "scrollX": true,
      "paging": true,
      "stateSave": true,
      "scrollCollapse": true
    });

    $("#bulanan").DataTable({

      "responsive": true,
      "bSort": true,
      // "scrollX": true,
      "paging": false,
      "stateSave": true,
      "scrollCollapse": true
    });

    // $("#neraca_saldo").DataTable({ 

    // "responsive": true,
    // "bSort": true,
    // "paging" : true,
    // "stateSave" : true,
    // "scrollCollapse" : true,
    // "order": [[ 1, "ASC" ]]
    // });

    $("#tb_akun").DataTable({

      "responsive": true,
      "bSort": true,
      // "scrollX": true,
      "paging": true,
      "stateSave": true,
      "scrollCollapse": true,
      "fixedHeader": true
    });

    $('#pengeluaran').DataTable({
      "paging": true,
      "lengthChange": true,
      "ordering": true,
      "info": true,
      "stateSave": true,
      "autoWidth": true,

    });

    $('#produkmasuk').DataTable({
      "paging": true,
      "lengthChange": true,
      "ordering": true,
      "info": true,
      "stateSave": true,
      "autoWidth": true,
      // "order": [ 5, 'DESC' ],
      // "searching": false,
    });


    $('#tb_servis').DataTable({
      "paging": false,
      "pageLength": 100,
      "scrollY": "350px",
      "lengthChange": false,
      "ordering": false,
      "info": false,
      "stateSave": true,
      "autoWidth": true,
      // "order": [ 5, 'DESC' ],
      // "searching": false,
    });

    //   $('#produk').DataTable({
    //   "paging": false,
    //   "pageLength": 100,
    //   "scrollY": "350px",
    //   "lengthChange": false,
    //   "ordering": false,
    //   "info": false,
    //   "stateSave": true,
    //   "autoWidth": true,
    //   // "order": [ 5, 'DESC' ],
    //   // "searching": false,
    // });

    $('#example3').DataTable({
      "paging": true,
      "lengthChange": true,
      "ordering": true,
      "stateSave": true,
      "info": true,
      // "order": [ 5, 'DESC' ],
      // "searching": false,
      "autoWidth": true,
    });

    $('#kelolaproduk').DataTable({
      "paging": true,
      "lengthChange": true,
      "ordering": true,
      "info": true,
      "stateSave": true,
      "autoWidth": true,
      "order": [5, 'ASC'],
      // "searching": false,
    });
    $('#produkterlaris').DataTable({
      "paging": true,
      "lengthChange": true,
      "ordering": true,
      "info": true,
      "stateSave": true,
      "autoWidth": true,
      // "order": [ 4, 'ASC' ],
      // "searching": false,
    });


  });

  //   $(document).ready(function() {
  //     var table = $('#pengeluaran').DataTable( {
  //         fixedHeader: {
  //             header: true,
  //             footer: true
  //         }
  //     } );
  // } );  
</script>

<script>
  (function(document) {
    'use strict';

    var LightTableFilter = (function(Arr) {

      var _input;
      var _select;

      function _onInputEvent(e) {
        _input = e.target;
        var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
        Arr.forEach.call(tables, function(table) {
          Arr.forEach.call(table.tBodies, function(tbody) {
            Arr.forEach.call(tbody.rows, _filter);
          });
        });
      }

      function _onSelectEvent(e) {
        _select = e.target;
        var tables = document.getElementsByClassName(_select.getAttribute('data-table'));
        Arr.forEach.call(tables, function(table) {
          Arr.forEach.call(table.tBodies, function(tbody) {
            Arr.forEach.call(tbody.rows, _filterSelect);
          });
        });
      }

      function _filter(row) {

        var text = row.textContent.toLowerCase(),
          val = _input.value.toLowerCase();
        row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';

      }

      function _filterSelect(row) {

        var text_select = row.textContent.toLowerCase(),
          val_select = _select.options[_select.selectedIndex].value.toLowerCase();
        row.style.display = text_select.indexOf(val_select) === -1 ? 'none' : 'table-row';

      }

      return {
        init: function() {
          var inputs = document.getElementsByClassName('light-table-filter');
          var selects = document.getElementsByClassName('select-table-filter');
          Arr.forEach.call(inputs, function(input) {
            input.oninput = _onInputEvent;
          });
          Arr.forEach.call(selects, function(select) {
            select.onchange = _onSelectEvent;
          });
        }
      };
    })(Array.prototype);

    document.addEventListener('readystatechange', function() {
      if (document.readyState === 'complete') {
        LightTableFilter.init();
      }
    });

  })(document);
</script>
</body>

</html>