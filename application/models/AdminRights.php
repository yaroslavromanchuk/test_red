<?php
class AdminRights extends wsActiveRecord
{
    protected $_table = 'admin_rights';
    protected $_orderby = array('id' => 'DESC');

    protected function _defineRelations()
    {

    }

    static function  getViewPages()
    {
        return wsActiveRecord::useStatic('Menu')->findAll(array('section > 0','type_id'=>2,'active'=>1), array('section' => 'ASC', 'sequence' => 'ASC'));
    }

    static function  getPages()
    {
        return wsActiveRecord::useStatic('Menu')->findAll(array('controller = "admin" or type_id =2','active'=>1), array('name' => 'ASC'));
    }

    static function issetRights($admin_id, $page_id)
    {
        $find = wsActiveRecord::useStatic('AdminRights')->findFirst(array('admin_id' => $admin_id, 'page_id' => $page_id));
        if ($find) return true;
        else return false;
    }

    static function  getAdminRights($admin_id)
    {
        return wsActiveRecord::useStatic('AdminRights')->findAll(array('admin_id' => $admin_id));
    }

    static function setRights($admin_id, $page_id, $val)
    {
        $find = wsActiveRecord::useStatic('AdminRights')->findFirst(array('admin_id' => $admin_id, 'page_id' => $page_id));
        if ($find) {
            $find->setRight($val);
        } else {
            $find = new AdminRights();
            $find->setAdminId($admin_id);
            $find->setPageId($page_id);
            $find->setRight($val);
        }
        $find->save();

    }
	static function getRights($page_id){
	return wsActiveRecord::useStatic('AdminRights')->findAll(array('page_id' => $page_id, 'right'=>1));
	}

    static function setViews($admin_id, $page_id, $val)
    {
        $find = wsActiveRecord::useStatic('AdminRights')->findFirst(array('admin_id' => $admin_id, 'page_id' => $page_id));
        if ($find) {
            $find->setView($val);
        } else {
            $find = new AdminRights();
            $find->setAdminId($admin_id);
            $find->setPageId($page_id);
            $find->setView($val);
        }
        $find->save();

    }
    static function destroyRights($admin_id){
        $rights = wsActiveRecord::useStatic('AdminRights')->findAll(array('admin_id'=>$admin_id));
        foreach($rights as $r){
            $r->destroy();
        }
    }


}

?>