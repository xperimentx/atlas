[Documentation](README.md) 
\ [Database reference](Database-reference.md)

![xperimentx atlas](images/atlas.png) 

|Method|Params |Returns|Info   |
|:--------|:------|:------|:------|
|Connect  |()|bool|Creates a new mysqli object and connects it to the MySQL server.|
|---------|-----|-----|-----|
|Query    |($query, $caller_method=null)|mixed,null| Performs a query on the database.|
|Query_ar |($query, $caller_method=null)|int,null| Performs a query on the database en returns the number of affected rows.|
|---------|-----|-----|-----|
|Scalar   |($query )| misc,null| Returns first column of first row of a query result.|
|Row      |($query, $class_name='stdClass')|object, null|Returns first row for a query as an object.|
|Rows     |($query, $class_name='stdClass')|array|Returns array of objects for a query statement|    
|Vector   |($query)|array|Returns a simple array index=>scalar_value from a query.|    
|---------|-----|-----|-----|
|Str      |($scalar)|string|Escapes special characters in a string for use in an SQL statement, between single quotes '.|    
|Safe     |($value)|string| Returns a safe value from a scalar for an SQL statement.|    
|---------|-----|-----|-----|
|Is_unique|($table_name, $field_value , $field_name, $key_value_to_ignore=null, $key_field_name='id')|bool|Checks if the value of the field is unique in the table.|    
|---------|-----|-----|-----|
|Insert|($table, $data, $do_safe = true )|int,null|Insert into statement.|    
|Update|($table, $data, $where=null, $do_safe=true )|int,null|Update statement.|    
|Update_or_insert|($table, $data, &$key_value , $key_field_name='id', $do_safe=true)|int|Update a row (key!=null) or Insert a new row  (key value=null)|    
|---------|-----|-----|-----|
|Drop_table     |($table, $if_exists=true)|int|Drops a table.|    
|Truncate_table |($table)|int|Truncates a table.|    
|Drop_database  |($database_name, $if_exists=true)|int|Drops a database.|    
|Drop_view      |($view_name, $if_exists=true)|int|Drops a view.|    
|Create_database||||    
|Show_columns   |($table)|||    
|Show_create_table|($table)|||    
|Show_create_database|($database_name, $if_not_exists=true)|||    
 

 