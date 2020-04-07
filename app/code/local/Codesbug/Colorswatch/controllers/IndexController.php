<?php

class Codesbug_Colorswatch_IndexController extends Mage_Core_Controller_Front_Action
{
    /*
     * isdir action called by colorswatch.js's hasImage, this function checks image directory exists or not
     */
    public function isdirAction()
    {
        $dir = $this->getRequest()->getPost('dir');
        $imageDirectory = Mage::getBaseDir() . DS . 'media' . DS . 'colorswatch' . DS . $dir;
        $directoryExist = array('yes' => is_dir($imageDirectory));
        return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($directoryExist));
    }
    /*
     * deleteimage called when ever user click on cross icon which appears when user hover on the image in admin panel.
     * This function delete the image and when all images are deleted then it will delete the whole directory also.
     */
    public function deleteimageAction()
    {
        $path = Mage::getBaseDir();
        $attributeId = $this->getRequest()->getPost('aId');
        $optionId = $this->getRequest()->getPost('oId');
        $imageDirectory = $path . DS . 'media' . DS . 'colorswatch' . DS . $attributeId . DS;
        $imageDirectory = $imageDirectory . DS . $optionId . DS . 'img';
        unlink($imageDirectory);
        $optionDriectory = $path . DS . 'media' . DS . 'colorswatch' . DS . $attributeId . DS . $optionId;
        rmdir($optionDriectory);
        $attributeDirectory = $path . DS . 'media' . DS . 'colorswatch' . DS . $attributeId . DS;
        rmdir($attributeDirectory);
    }
}
