<?php

class Codesbug_Colorswatch_Model_Observer
{
    /*
     * saveImages() runs on event catalog_entity_attribute_save_after, It creats a directory in media/colorswatch/product_id/img
     * it update the image also
     */

    public function saveImages(Varien_Event_Observer $event)
    {
        $file = new Varien_Io_File();
        $path = Mage::getBaseDir();
        $attributeId = (int) $event->getData('event')->getDataObject()->getAttributeId();
        $model = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
        $options = $model->getSource()->getAllOptions(false);
        $fileIndex = 0;
        $allowedExtentions = array('png', 'jpeg', 'gif', 'jpg');

        foreach ($options as $value) {
            $file_tmp = $_FILES['image']['tmp_name'][$fileIndex];
            $name = $_FILES['image']['name'][$fileIndex];
            $type = pathinfo($name, PATHINFO_EXTENSION);
            $fullPath = $path . DS . 'media' . DS . 'colorswatch' . DS . $attributeId . DS;
            if (!in_array($type, $allowedExtentions) && !empty($name)) {
                $error = (string) Mage::app()->getConfig()->getNode('global/messages/error/invalid_file');
                Mage::getSingleton('core/session')->addError($error);
            } else if (!empty($name)) {
                $fullPath = $path . DS . 'media' . DS . 'colorswatch' . DS . $attributeId . DS . $value['value'] . DS;
                $file->rmdir($fullPath);
                $file->mkdir($fullPath, 0777, true);
                $fullPath = $fullPath . 'img';
                move_uploaded_file($file_tmp, $fullPath);
            }
            $fileIndex++;
        }
    }
}
