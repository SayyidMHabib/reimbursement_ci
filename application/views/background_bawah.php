</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
  <div class="p-3">
    <h5>Title</h5>
    <p>Sidebar content</p>
  </div>
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<footer class="main-footer">
  <!-- To the right -->
  <!-- Default to the left -->
  <strong>Copyright &copy; <?= date('Y'); ?>.</strong> All Right Reserved.
</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

</body>
<!-- Custom -->
<!-- <script src="<?= base_url("assets"); ?>/dist/js/ubah_pass.js"></script> -->
<script>
  $("#up_infoalert").hide();


  $("#frm_ubahpass").on("submit", function(e) {
    e.preventDefault();
    $('#up_simpan').text('Menyimpan...'); //change button text
    $('.btn').attr('disabled', true); //set button enable 
    $.ajax({
      url: "../Login/ubah_pass",
      type: "POST",
      data: $('#frm_ubahpass').serialize(),
      dataType: "JSON",
      success: function(data) {

        if (data.status) //if success close modal and reload ajax table
        {
          $("#up_infoalert").removeClass("alert-danger");
          $("#up_infoalert").addClass("alert-success");
          $("#up_pesan").html(data.pesan);
          $("#up_infoalert").fadeIn();
          setTimeout(function() {
            document.location.href = '';
          }, 2000);
        } else {
          $("#up_infoalert").removeClass("alert-success");
          $("#up_infoalert").addClass("alert-danger");
          $("#up_pesan").html(data.pesan);
          $("#up_infoalert").fadeIn().delay(2000).fadeOut();
          $('.btn').attr('disabled', false); //set button enable 
        }
        $('#up_simpan').text('Simpan'); //change button text
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert(jqXHR + " - " + textStatus + " - " + errorThrown);
        $('#up_simpan').text('Simpan'); //change button text
        $('.btn').attr('disabled', false); //set button enable 

      }
    });
  })
</script>

</html>