<?php

namespace Modules\Saas\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class SaasTableList extends Model
{
    public static function getTableList($id){

        $db_name=env('DB_DATABASE', null);
		$table_list=DB::select("SELECT TABLE_NAME 
		FROM INFORMATION_SCHEMA.COLUMNS
		WHERE COLUMN_NAME ='$id'
			AND TABLE_SCHEMA='$db_name'");

			$tables="";
			
			foreach($table_list as $row){
				$name = str_replace('sm_', '', $row->TABLE_NAME);
				$name = str_replace('_', ' ', $name);
				$name = ucfirst($name);
				$tables.=$name.', ';
            }
            return $tables;

	}
	

    
}