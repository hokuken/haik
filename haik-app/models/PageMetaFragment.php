<?php
class PageMetaFragment extends Eloquent {

    protected $table = 'haik_page_meta_fragments';

    public $timestamps = false;

    protected $fillable = array('haik_page_id', 'key', 'value');

}
