<?php include('variable.php');?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin Panel | All News</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 <?php include('include/headercss.php');?>
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php //include('header.php');?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php //include('menu.php');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Create Badges
      </h1>  
    <div style="color:red">
	   <?php echo validation_errors(); ?>
       <?php if(isset($error)){?><?php echo $error;}?>
	   </div>
		<?php

		if($this->session->flashdata('item')) {
		$message = $this->session->flashdata('item');
		?>
		<div class="<?php echo $message['class'] ?>"><?php echo $message['message']; ?>

		</div>
		<?php
		}
		?>	  
    </section>

    <!-- Main content -->
    <section class="content">
	
	  <!-- Data tables-->
	  <div class="box">
            <div class="box-header">
              <h3 class="box-title" >PTI News List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sno</th>
                  <th>Tittle</th>
                  <th>Remark</th>
                  <th>Img</th>
				  <th>CreatedBy</th>
				  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
		
			<?php /*$sno=1;
			//if(is_array($badge_records)):
			//foreach ($badge_records as $records): 
			?>
                <tr>
                  <td><?=$sno;?></td>
                  <td><?=$records->Badges_Tittle;?></td>
                  <td><?=$records->Badges_Remark;?></td>
                  
                  <td><img src="<?=base_url().'assets/uploads/'.$records->Badges_Img;?>" width="35"></td>
				  
                  <td><?=$records->UserName;?></td>
                  <td><?php if($records->Badges_Sts === 'A'){ ?><span style="color:green;">Active</span><?php } else{ ?><span style="color:red;"> Deactive </span><?php } ?></td>
				  <td><a href="<?php echo site_url('badge/add_badgedata/'.$records->BadgesId); ?>"><img title="Edit" src="<?= base_url()?>assets/img/edit.png" /></a> <a href="<?php echo site_url('badge/delete_badgedata/'.$records->BadgesId); ?>"><img title="Delete" src="<?= base_url()?>assets/img/delete1.png" onClick="return confirm('Are you sure you want to delete?')" /></a></td>
                </tr>
				<?php
                 $sno++;
				endforeach;
                endif;				
			   */ ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
	  
	  
	  
	  <!-- DataTable -->
	  
	  
      <!-- /.row -->
    </section>
     
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 <?php include('footer.php');?>
  <!-- Control Sidebar -->
</div>
<!-- ./wrapper -->
<?php include('include/footerjs.php');?>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
	$('#example1').DataTable()
  })
</script>
<script type="application/javascript">
/** After windod Load */
$(window).bind("load", function() {
  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove();
    });
}, 4000);
});
</script>
</body>
</html>
