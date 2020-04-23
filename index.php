<?php
include ('functions.php');

//Example 1
/*Search data based on 2 tables starts*/
$data['table_details']=array(
	'table_name_1'=>array(//first_table
		'column_1'=>array(//first_table_column_name_1
			'as'=>'column_1_alias'// alais is not nocessary
		), 
		'column_2'=>array(//first_table_column_name_2
			'as'=>'column_2_alias'// alais is not nocessary
		), 
	)
);
/*Used based on the requirement else not required to used search_criteria*/
$data['search_criteria']=array(
	array(
		'operator'=>'AND',// if operator is not declared AND will be the default operator
		'column'=>'table_name.column_name',
		'value'=>'searching_value1',
	)
);
/*Used based on the requirement else not required to used search_criteria*/
/*Used based on the requirement else not required to used sorting*/
$data['result_sort']=array(
	array(
		'column'=>'table_name.column_name',
		'sort'=>'ASC',
		'limit' =>'LIMIT 1'// not necessary but based on the requirement it may be used else simply comment the line
	),
);
/*Used based on the requirement else not required to used sorting*/

print_r(check_record_availability(json_encode($data)));
/*Search based single tables ends*/

//Example 2
/*Search data based on 2 tables starts*/
$data['table_details']=array(
	'table_name_1'=>array(//first_table
		'column_1'=>array(//first_table_column_name_1
			'as'=>'column_1_alias'// alais is not nocessary
		), 
		'column_2'=>array(//first_table_column_name_2
			'as'=>'column_2_alias'// alais is not nocessary
		), 
	), 
	'table_name_2'=>array(//second_table
		'column_1'=>array(//second_table_column_name_1
			'as'=>'column_3_alias'// alais is not nocessary
		), 
		'column_2'=>array(//second_table_column_name_2
			'as'=>'column_4_alias'// alais is not nocessary
		), 
	)
);
$data['table_connection']=array(
	array(
		'operator'=>'AND',
		'fk'=>'table_name_1.column_name',
		'pk'=>'table_name2.column_name',
		//operator fk_table.column_name=pk_table.column_name
	)
);
print_r(check_record_availability(json_encode($data)));
/*Search based on 2 tables ends*/

//Example 3
/*Search data based on 3 tables starts*/
$data['table_details']=array(
	'table_name_1'=>array(//first_table
		'column_1', 
		'column_2', 
	), 
	'table_name_2'=>array(//second_table
		'column_3', 
		'column_4', 
	), 
	'table_name_3'=>array(//third_table
		'column_5', 
		'column_6', 
	),
);

$data['table_connection']=array(
	array(
		'operator'=>'AND',//
		'fk'=>'table_name_1.column_name',
		'pk'=>'table_name2.column_name',
		//operator fk_table.column_name=pk_table.column_name
	),
	array(
		'operator'=>'AND',
		'fk'=>'table_name_2.column_name',
		'pk'=>'table_name_3.column_name',
		//operator fk_table.column_name=pk_table.column_name
	)
);

$data['search_criteria']=array(
	array(
		'operator'=>'AND',
		'column'=>'table_name.column_name',
		'value'=>'searching_value1',
	)
);

$data['result_sort']=array(
	array(
		'column'=>'table_name.column_name',
		'sort'=>'ASC',
		'limit' =>'LIMIT 1'// not necessary but based on the requirement it may be used else simply comment the line
	),
);

print_r(check_record_availability(json_encode($data)));
/*Search based on 3 tables ends*/

?>
