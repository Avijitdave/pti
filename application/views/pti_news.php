
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin Panel | All News</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 <?php print_r($header); ?>
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
	  <input type="hidden" id="baseUrl" value="<?php echo base_url();?>">
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
				  <option>National</option>

                </select>
              </div>
			 
			  </div>
		</div>
		<div class="col-md-6">
			<div class="card-footer">
                  <button type="button" class="btn btn-success" id="feed_pub">Publish News TO Website</button>
                  <a class="btn btn-info" href="<?php echo base_url();?>Pti_news/archive">Pti News Archive</a>
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
				  <th>News Category</th>
                  <th>News Tittle</th>
                  
                  <th>content</th>
				  <th>State</th>
				  <th>City</th>
                </tr>
                </thead>
                <tbody>
                <?php if(count($feeds)>0){
                    $c = 1;
                    foreach($feeds as $feed){ ?>
                  		
                  		<tr>
        					<td>
            					<div class="icheck-danger d-inline">
                                    <input type="checkbox" class="singlechkbox" id="checkboxDanger_<?php echo $c;?>" data-id="<?php echo $c;?>" name="news" value="<?php echo $feed['guid']; ?>">
                                    <label for="checkboxDanger_<?php echo $c; ?>"></label>
                                </div>							
        					</td>
        					<td><?php
        					if($feed['category_name_english'] == 'Country'){
        					    echo 'National';
        					} else {
        					   echo $feed['category_name_english']; 
        					}  ?></td>
        					<!--td><?php //echo $feed['categories']; ?></td-->	
        					
        				  	<td><?php echo $feed['title']; ?></td>
        				  	<td><?php echo substr($feed['content'],0,300); ?></td>
        				  	<td><?php echo $feed['state_name_english']; ?></td>
        				  	<td><?php echo $feed['city_name_english']; ?></td>
        				</tr>
                  		      
                <?php $c++; }
                } ?>
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
        var baseUrl = $('#baseUrl').val();
        
        $('body').on('click', '#checkboxSuccess1', function() {
              $('.singlechkbox').prop('checked', this.checked);
        });
 
        $(document).on('click', '.singlechkbox', function() {
            var id = $(this).data('id');
            
            if($(".singlechkbox").length == $(".singlechkbox:checked").length) {
                $("#checkboxSuccess_"+id).prop("checked", "checked");
            } else {
                $("#checkboxSuccess_"+id).removeAttr("checked");
            }
 
        });

		$(document).on('click','#feed_pub',function(){
			var newsIds = [];
			$("input:checkbox[name=news]:checked").each(function(){
				console.log($(this).val());
			    newsIds.push($(this).val());
			});


			$.ajax({
				type: 'POST',
				url: baseUrl+'Pti/pti_submit',
				data: {
					'news' : newsIds
				},
				dataType: 'json',
				beforeSend: function() {
				},
				success: function(response){
					alert('done');
					location.reload(true);
				}
			});
			
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
