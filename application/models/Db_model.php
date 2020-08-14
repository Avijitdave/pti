<?php
class Db_model extends CI_Model{
	
/*function get_pti_records(){
		$this->db->select("tbl_aboutus.AboutContent,tbl_aboutus.AboutSts,tbl_aboutus.CrDate,tbl_aboutus.CrBy,tbl_aboutus.AboutId,tbl_user.UserName");
		$this->db->from('tbl_aboutus');
		$this->db->join('tbl_user', 'tbl_user.UserId = tbl_aboutus.CrBy');
		$this->db->order_by("AboutId", "desc");
		$all_records = $this->db->get();
		return $all_records->result();
	}*/

}
?>