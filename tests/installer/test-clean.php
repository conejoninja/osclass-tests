<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestFrontend.php';
class TestCleanExample extends OsclassTestFrontend
{

    public function testRemoveExample()
    {
        $aItem = Item::newInstance()->listAll();
        foreach($aItem as $item){
            $url = osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] );
            $this->open( $url );
            $this->assertTrue($this->isTextPresent("Your listing has been deleted"), "Delete item.");
        }

    }

}
