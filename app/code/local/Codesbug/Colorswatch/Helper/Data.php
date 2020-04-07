<?php

class Codesbug_Colorswatch_Helper_Data extends Mage_Catalog_Helper_Product_Configuration
{
    public function getConfigurableOptions(Mage_Catalog_Model_Product_Configuration_Item_Interface $item)
    {
        $product = $item->getProduct();
        $typeId = $product->getTypeId();
        if ($typeId != Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE) {
            Mage::throwException($this->__('Wrong product type to extract configurable options.'));
        }
        $data = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
        $superAttributes = $data['info_buyRequest']['super_attribute'];
        $attributes = $product->getTypeInstance(true)
            ->getSelectedAttributesInfo($product);
        $index = 0;
        foreach ($superAttributes as $attribute => $value) {
            $imageUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'colorswatch' . DS . $attribute . DS . $value . DS . 'img';
            $alt = $attributes[$index]['value'];
            $attributes[$index]['value'] = '<img style="width:25px; height:25px;" src="' . $imageUrl . '" alt="' . $alt . '">';
            $index++;
        }
        return array_merge($attributes, $this->getCustomOptions($item));
    }
    public function getFormattedOptionValue($optionValue, $params = null)
    {
        // Init params
        if (!$params) {
            $params = array();
        }
        $maxLength = isset($params['max_length']) ? $params['max_length'] : null;
        $cutReplacer = isset($params['cut_replacer']) ? $params['cut_replacer'] : '...';
        $maxLength = 1000;
        // Proceed with option
        $optionInfo = array();

        // Define input data format
        if (is_array($optionValue)) {
            if (isset($optionValue['option_id'])) {
                $optionInfo = $optionValue;
                if (isset($optionInfo['value'])) {
                    $optionValue = $optionInfo['value'];
                }
            } else if (isset($optionValue['value'])) {
                $optionValue = $optionValue['value'];
            }
        }

        // Render customized option view
        if (isset($optionInfo['custom_view']) && $optionInfo['custom_view']) {
            $_default = array('value' => $optionValue);
            if (isset($optionInfo['option_type'])) {
                try {
                    $group = Mage::getModel('catalog/product_option')->groupFactory($optionInfo['option_type']);
                    return array('value' => $group->getCustomizedView($optionInfo));
                } catch (Exception $e) {
                    return $_default;
                }
            }
            return $_default;
        }

        // Truncate standard view
        $result = array();
        if (is_array($optionValue)) {
            $_truncatedValue = implode("\n", $optionValue);
            $_truncatedValue = nl2br($_truncatedValue);
            return array('value' => $_truncatedValue);
        } else {
            if ($maxLength) {
                $_truncatedValue = Mage::helper('core/string')->truncate($optionValue, $maxLength, '');
            } else {
                $_truncatedValue = $optionValue;
            }
            $_truncatedValue = nl2br($_truncatedValue);
        }

        $result = array('value' => $_truncatedValue);

        if ($maxLength && (Mage::helper('core/string')->strlen($optionValue) > $maxLength)) {
            $result['value'] = $result['value'] . $cutReplacer;
            $optionValue = nl2br($optionValue);
            $result['full_view'] = $optionValue;
        }

        return $result;
    }
}
