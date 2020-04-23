<?php
include ('config.php');
function check_record_availability($data){
	$res=json_decode($data,true);
	$table_details=$res['table_details'];
	$errorno=1;
	if(isset($res['table_connection'])){
		$table_connection=$res['table_connection'];
	}else{
		$errorno=0;
		$errormsg="Table data could not found. Please set the table data";
	}
	if(isset($res['search_criteria'])){
		$search_criteria=$res['search_criteria'];
	}
	if(isset($res['result_sort'])){
		$result_sort=$res['result_sort'];
	}
	
	if($errorno==1){
		$tables=$columns=$where_clause=$search=$sort="";
		foreach ($table_details as $k=>$v){
			$tables.="$k, ";
			if(is_array($v)==1){
				foreach ($v as $vk=>$vv){
					$columns.="$k.$vk";
					if(is_array($vv)==1){
						foreach ($vv as $vvk=>$vvv){
							if($vvk=='as'){
								$columns.=" $vvk $vvv";
							}
						}//if the column alais are declared, this code will execute
					}
					$columns.=", ";
				}
			}
		}//getting the tables and columns need to output
		$tables=rtrim($tables,', '); $columns=rtrim($columns,', ');

		if(isset($table_connection) && !empty($table_connection)){
			$where_clause=" WHERE ";
			for($i=0;$i<count($table_connection);$i++){
				$tc=$table_connection[$i];
				if(!isset($tc['operator'])){
					$tc['operator']=" AND ";
				}
				if($i==0){
					$tc['operator']=" ";
				}
				$where_clause.="$tc[operator] $tc[fk]= $tc[pk]";
			}
		}//make the relevant table connections if a single table, this block wont be used

		$paramTypes="";
		$valuesArray=array();
		if(isset($search_criteria)&& !empty($search_criteria)){
			if(count($search_criteria)>0){
				$c=0;
				for($i=0;$i<count($search_criteria);$i++){
					$sc=$search_criteria[$i];
					if(!isset($sc['operator'])){
						$sc['operator']="AND";
					}
					if($i==0){
						$sc['operator']="AND (";
						if($where_clause==""){
							$sc['operator']="WHERE (";	
						}				
					}
					$search.=" ".$sc['operator']." ".$sc['column']."=(?)";
					$paramTypes.='s';
					$valuesArray[]=$sc['value'];
				}
				$search.=')';
			}
		}//Delaring the search criteria 

		if(isset($result_sort)){
			if(count($result_sort)>0){
				$c=0;
				for($i=0;$i<count($result_sort);$i++){
					$rs=$result_sort[$i];

					if(isset($rs['column'])){
						if(!isset($rs['sort'])){
							$rs['sort']="ASC";
						}
						$sort=" ORDER BY $rs[column] $rs[sort]";
					}
					if(isset($rs['limit'])){
						$sort.=" $rs[limit]";
					}
				}
			}
		}// setting up the sorting 

		$conn=config();
		$query="SELECT $columns FROM $tables $where_clause $search $sort";// joining the query
		$inputArray[] = &$paramTypes;
		$j = count($valuesArray);
		for($x=0;$x<$j;$x++){$inputArray[] = &$valuesArray[$x];}

		$stmt = mysqli_prepare($conn,$query);
		if ($stmt) {
			if($j>0){
				call_user_func_array(array($stmt, 'bind_param'), $inputArray);
			}
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			mysqli_stmt_store_result($stmt);
			while ($row = mysqli_fetch_assoc($result)) {
				$rows[]=$row;
				$errormsg="Success";
			}
			if(!isset($rows)){
				$errorno=0;
				$errormsg="Could not fould any data for the searched criteria";
			}
		}else{
			$errorno=0;
			$errormsg="Something went wrong";
		}
	}
	mysqli_stmt_close($stmt);
	
	$result_data['errorno']=$errorno;
	$result_data['errormsg']=$errormsg;
	if($errorno==1){
		$result_data['result']=$rows;
	}
	return json_encode($result_data);
	exit();
}
?>
