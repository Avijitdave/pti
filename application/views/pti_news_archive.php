
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Pti Archive | All News</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 <?php print_r($header); ?>
 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php //include('header.php');?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php //include('menu.php');?>
  <!-- Content Wrapper. Contains page content -->
  
  <div class="content-wrapper ">

    <!-- Main content -->
    <section class="content">
	
	
	  <!-- Data tables-->
	  <input type="hidden" id="baseUrl" value="<?php echo base_url();?>">
	  <div class="box">
            <div class="box-header">
                  <h3 class="box-title" >PTI News List</h3><br><br>
    			  <div class="col-md-4">
        			<div class="form-group">
                        <label class="col-sm-2 control-label">Select Category: </label>
        				 <div class="col-sm-6"  >
                           <select class="form-control select2" id="table-filter">
                              <option value="">All</option>
            				  <option>Published</option>
            				  <option>On Hold</option>
                            </select>
                      	</div>
        			</div>
    			</div>
    			
    			<div class="col-md-4">
        			<div class="form-group">
        				 <div class="col-sm-12">
        				   <label class="col-sm-2 control-label">Select Date: </label>
                           <input type="text" name="daterange" id="daterange" value="<?php if($this->uri->segment(3) != ''){ echo $this->uri->segment(3); } else {echo date('m-d-Y'); } ?> - <?php if($this->uri->segment(4) != ''){ echo $this->uri->segment(4); } else {echo date('m-d-Y'); } ?>" />
                      	</div>
        			</div>
    			</div>
    			
        		<div class="col-md-2">
        			<div class="card-footer">
        			  <input type="button" class="btn btn-info" id="search" value="Search">
                      <a class="btn btn-success" href="<?php echo base_url();?>Pti_news">Go Home</a>
                    </div>
        		</div>
            </div>
			
			
            
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
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
				  <th>Status</th>
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
        				  	<td>
        				  		<?php if($feed['status'] == '0'){ ?>
        				  			<span class="text-success text-bold">Published</span>
        				  		<?php } else { ?>
        				  			<span class="text-danger text-bold">On Hold</span>
        				  		<?php } ?>
        				  	</td>
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


$('input[name="daterange"]').daterangepicker({
    opens: 'right'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });


function escapeRegExp(string){
    return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
}
function replaceAll(str, term, replacement) {
  return str.replace(new RegExp(escapeRegExp(term), 'g'), replacement);
}

$(document).on('click','#search',function(){
	var date = $('#daterange').val();
	var res = date.split("-");

	res[0] = replaceAll(res[0], '/', '-');
	res[1] = replaceAll(res[1], '/', '-');

	var href = "<?php echo base_url();?>pti_news/archive/"+res[0].trim()+"/"+res[1].trim();
	 
	window.location.href = href;
});
</script>
</body>
</html>
