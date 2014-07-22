<?php
class Device
{
    public $id;
    public $commands = array();
    public $labels = array();
    public $key_type = array();

    public function __construct($id) {
        $this->id = $id;
    }
}
?>
