<?php
class ConfigFragment extends Eloquent {

    protected $table = 'haik_config_fragments';

    public $timestamps = false;

    protected $fillable = array('key', 'value');

}
