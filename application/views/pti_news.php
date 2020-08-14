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
  
  <div class="content-wrapper ">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     
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
              <h3 class="box-title" >PTI News List</h3><br><br>
			  <div class="col-md-6">
			<div class="form-group">
                <label class="col-sm-2 control-label">Select Category: </label>
				 <div class="col-sm-6"  >
               <select class="form-control select2" id="table-filter">
                  <option value="">All</option>
				  <option>Chhattisgarh</option>
				  <option>Madhya Pradesh</option>		
				  <option>Maharashtra</option>
				  <option>Bihar</option>
				  <option>Uttar Pradesh</option>
				  <option>National</option>
				  <option>World</option>
				  <option>Sport</option>
				  <option>Business</option>				  
                </select>
				
                 
                
              </div>
			 
			  </div>
		</div>
		<div class="col-md-6">
			<div class="card-footer">
                  <button type="button" class="btn btn-success">Publish News TO Website</button>
                </div>
		</div>
			  
            </div>
			
			
			
            <!-- /.box-header -->
            <div class="box-body">
			 
              <table id="example1" class="table table-bordered table-striped">
			   <!--table id="example1" class="display" cellspacing="0" width="100%"-->
                <thead>
                <tr>
                  <th>
				  <div class="icheck-success d-inline">
                        <input type="checkbox" id="checkboxSuccess1">
                        <label for="checkboxSuccess1">
                        </label>
                      </div>
				  
				  
				  
				  </th>
                  <th>Tittle</th>
                  <th>Remark</th>
                  <th>Img</th>
				  <th>CreatedBy</th>
				  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				
							<tr>
							
							<td>
							 <div class="icheck-danger d-inline">
                        <input type="checkbox" class="singlechkbox" id="checkboxdanger1" name="news[]" value="1">
                        <label for="checkboxdanger1">
                        </label>
                      </div>							
							</td>
							
							
				  <td>Tiger Nixon</td>
				  <td>System Architect</td>
				  <td>Edinburgh</td>
				  <td>61</td>
				  <td>2011/04/25</td>
				  <td>$320,800</td>
				</tr>
				<tr>
				
				 <td> 
   <div class="icheck-danger d-inline">
                        <input type="checkbox" class="singlechkbox" id="checkboxdanger2" name="news[]" value="2">
                        <label for="checkboxdanger2">
                        </label>
                      </div>
  </td>
				
				  <td>Garrett Winters</td>
				  <td>Accountant</td>
				  <td>Tokyo</td>
				  <td>63</td>
				  <td>2011/07/25</td>
				  <td>$170,750</td>
				</tr>
				<tr>
				
				 <td> 
  <div class="icheck-danger d-inline">
                        <input type="checkbox" class="singlechkbox" id="checkboxdanger3" name="news[]" value="3">
                        <label for="checkboxSuccess3">
                        </label>
                      </div></td>
				
				  <td>Ashton Cox</td>
				  <td>Junior Technical Author</td>
				  <td>San Francisco</td>
				  <td>66</td>
				  <td>2009/01/12</td>
				  <td>$86,000</td>
				</tr>
				<tr>
				
				 <td> 
   <div class="icheck-danger d-inline">
                        <input type="checkbox" class="singlechkbox" id="checkboxdanger4" name="news[]" value="4">
                        <label for="checkboxdanger4">
                        </label>
                      </div>
  
</td>
				
				  <td>Cedric Kelly</td>
				  <td>Senior Javascript Developer</td>
				  <td>Edinburgh</td>
				  <td>22</td>
				  <td>2012/03/29</td>
				  <td>$433,060</td>
				</tr>
		
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
  /*$(function () {
	$('#example1').DataTable({
		//'iDisplayLength': 100,
		dom: 'lrtip'
		
	});
	 $('#table-filter').on('change', function(){
		 alert('ok');
       table.search(this.value).draw();   
    });
  })*/
  
  $(document).ready(function (){
    var table = $('#example1').DataTable({
	   'iDisplayLength': 100,
       dom: 'lrtip'
    });
    
    $('#table-filter').on('change', function(){
       table.search(this.value).draw();   
    });
});
 
 
</script>
 <script type="text/javascript">
    jQuery(function($) {
        $('body').on('click', '#checkboxSuccess1', function() {
              $('.singlechkbox').prop('checked', this.checked);
        });
 
        $('body').on('click', '.singlechkbox', function() {
            if($(".singlechkbox").length == $(".singlechkbox:checked").length) {
                $("#checkboxSuccess1").prop("checked", "checked");
            } else {
                $("#checkboxSuccess1").removeAttr("checked");
            }
 
        });
    });
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
