<?php
class Codesbug_Colorswatch_Block_Adminhtml_Imageupload extends Mage_Adminhtml_Block_Catalog_Product_Attribute_Edit_Tab_Options
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_colorswatch';
        $this->_blockGroup = 'colorswatch';
        $this->_headerText = Mage::helper('colorswatch')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('colorswatch')->__('Add Item');
        parent::__construct();
        $this->setTemplate('colorswatch/colorswatch.phtml');
    }
}
