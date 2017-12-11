[Documentation](README.md) 
\ [Database reference](Database-reference.md)

![xperimentx atlas](images/atlas.png) 

|Method   |Info   |
|:--------|:------|
|Connect  () :bool|Creates a new mysqli object and connects it to the MySQL server.|


|Method   |Info   |
|:--------|:------|
|Query    |($query, $caller_method=null) :mixed,null| Performs a query on the database.|
|Query_ar |($query, $caller_method=null) :int,null| Performs a query on the database en returns the number of affected rows.|


|Method   |Info   |
|:--------|:------|
|Scalar   ($query ) :misc,null| Returns first column of first row of a query result.|
|Row      ($query, $class_name='stdClass') :object, null|Returns first row for a query as an object.|
|Rows     ($query, $class_name='stdClass') :array|Returns array of objects for a query statement|    
|Column ($query) :array|Returns a the first column of a query as array.|
|Vector   ($query) :array|Returns a simple array index=>scalar_value from a query.|    


|Method   |Info   |
|:--------|:------|
|Str       ($scalar) :string|Escapes special characters in a string for use in an SQL statement, between single quotes '.|    
|Safe      ($value) :string| Returns a safe value from a scalar for an SQL statement.|    
|Is_unique ($table_name, $field_value , $field_name, $key_value_to_ignore, $key_field_name) :bool|Checks if the value of the field is unique in the table.|    


|Method   |Info   |
|:--------|:------|
|Insert ($table, $data, $do_safe = true ):int,null|Insert into statement.|    
|Update ($table, $data, $where=null, $do_safe ) :int,null|Update statement.|    
|Update_or_insert ($table, $data, &$key_value , $key_field_name, $do_safe):int|Update a row (key!=null) or Insert a new row  (key value=null)|    


 