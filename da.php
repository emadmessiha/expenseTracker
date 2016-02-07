<?php
class tbl_stores{
 public static function tableName(){ return 'stores';}
 public static function getColumnsArray(){return array_keys(get_class_vars(tbl_stores));}
 public static function getPrimaryKeyField(){ return 'id';}
 public static $store_name = 'store_name';
 public static $default_tag_id = 'default_tag_id';
}
class tbl_tags{
 public static function tableName(){ return 'tags';}
 public static function getColumnsArray(){return array_keys(get_class_vars(tbl_tags));}
 public static function getPrimaryKeyField(){ return 'id';}
 public static $tag_name = 'tag_name';
}
class tbl_transactions{
 public static function tableName(){ return 'transactions';}
 public static function getColumnsArray(){return array_keys(get_class_vars(tbl_transactions));}
 public static function getPrimaryKeyField(){ return 'id';}
 public static $tdate = 'tdate';
 public static $in_amount = 'in_amount';
 public static $out_amount = 'out_amount';
 public static $category = 'category';
 public static $description = 'description';
 public static $ttype = 'ttype';
 public static $tmonth = 'tmonth';
 public static $tyear = 'tyear';
 public static $tday = 'tday';
 public static $istransfer = 'istransfer';
 public static $store_id = 'store_id';
 public static $tag_id = 'tag_id';
}
class db_enum_ttype { function __construct() {} const c_visa='visa'; const c_checking='checking'; }
?>