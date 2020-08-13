<?php 
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(error_reporting() & (-1 ^ E_DEPRECATED));
/*$login_id = $this->session->userdata('userid');
$username = $this->session->userdata('username');
$userimg  =   $this->session->userdata('user_img');  	 
 function ddmmyy($getdate)
{
  $getdate = str_replace("/","-",$getdate);	 
  if($getdate!="" && $getdate!='0000-00-00')
  {	
   $newdate =  date("d-m-Y", strtotime($getdate));
   return $newdate;
  }
} */
?>