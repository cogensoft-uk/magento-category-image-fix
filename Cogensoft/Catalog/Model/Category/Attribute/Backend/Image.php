<?php

namespace Cogensoft\Catalog\Model\Category\Attribute\Backend;

class Image extends \Magento\Catalog\Model\Category\Attribute\Backend\Image
{
	/**
	 * @var string
	 */
	private $additionalData = '_additional_data_';

	public function beforeSave($object)
	{
		$attributeName = $this->getAttribute()->getName();
		$previousImage = $object->getData($attributeName);
		$previousImageName = $this->getUploadedImageName($previousImage);
		$result = parent::beforeSave($object);

		//Workaround for category image rename on save - https://github.com/magento/magento2/issues/25099
		$newImage = $object->getData($this->additionalData . $attributeName);
		$newImageName = $object->getData($attributeName);

		if($newImageName != $previousImageName && $newImage == $previousImage) {
			$object->setData($attributeName, $previousImageName);
		}

		return $result;
	}

	private function getUploadedImageName($value)
	{
		if (is_array($value) && isset($value[0]['name'])) {
			return $value[0]['name'];
		}

		return '';
	}
}