<?php
require_once BASEPATH . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "My_model.php";
class Network_model extends My_model
{
    protected $_table = 'NETWORK';
    protected $table_primary_key = "id_network";
}
