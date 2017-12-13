[xperimentX Atlas documentation](README.md) 

![xperimentx atlas](images/atlas.png)

#Active record


Id, primary key, auto numeric.

Attributes that begin with '_' do not belong to the record in the database,
they will be configuration properties or auxiliary properties.

Load_from* functions call  On_load() when they load successfully.

## Model setup
- Redeclare $_table to set the model table.
- If name in not your name field redeclare $_name_field;
- In not use the default Db object redeclare $_db.
- In your object use Obj_* cache redeclare  $_obj_cache_used_by_default.


##Cache during the execution.

_obj_cache_used_by_default = TRUE, when Obj_load($id) is called the object is registered,
if an object with the same id is requested again, the previously loaded object is returned.
You must redeclare this property*


##Field names.
id            Required. Primary key.
date_created. Opcional. Date time
date_modified Opcional. Last modification date

