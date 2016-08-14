<?php namespace ServiceInterface;
 
interface BaseServiceInterface {

  public function findById($id, $cache = null);
  public function query($query = null, $search = null, $orderBy = null, $paging = null, $cache = null);
  public function store($data);
  public function update($id, $data);
  public function destroy($id);
  public function instance();
  public function validate($data, $rules);
  
}