<?php
class relOpeAverAttenTime_model extends Model{
	
	
	function getResponseTime($date_interval, $condition)
	{
		$sql = 	"
                SELECT
                   d.name,
                   (SELECT
                      p.name
                   FROM
                      tbperson p
                   WHERE p.idperson = a.idperson_juridical) AS company,
                   MIN(b.MIN_ATTENDANCE_TIME) AS min_time,
                   ROUND(AVG(b.MIN_ATTENDANCE_TIME), 2) AS avg_time,
                   MAX(b.MIN_ATTENDANCE_TIME) AS max_time
                FROM
                   hdk_tbrequest a,
                   hdk_tbrequest_times b,
                   hdk_tbrequest_in_charge c,
                   tbperson d
                WHERE c.type = 'P'
                   AND DATE(a.entry_date) BETWEEN '2015-10-01'
                   AND '2015-11-30'
                   AND b.CODE_REQUEST = a.code_request
                   AND c.code_request = b.CODE_REQUEST
                   AND d.idperson = c.id_in_charge
                   AND c.ind_in_charge = 1
                   AND b.MIN_OPENING_TIME > 0

                $condition

                GROUP BY a.idperson_juridical,c.id_in_charge
                ORDER BY d.name,company ASC
				";
				
		//die($sql);

        $ret = $this->select($sql);
        if(!$ret) {
            $sError = "Arq: " . __FILE__ . " Line: " . __LINE__ . "<br>DB ERROR: " .  $this->db->ErrorMsg();
            $this->error($sError);
            return false;
        }
		
        return $ret;
    }
}
	
	  