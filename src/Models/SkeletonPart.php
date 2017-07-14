<?php

namespace DNADesign\ElementalSkeletons\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\DropdownField;
use DNADesign\Elemental\Extensions\ElementalAreasExtension;

/**
 * Creates a archetype of elements that can be used as a template that is defined
 * within the CMS
 */
class SkeletonPart extends DataObject {

	private static $db = array(
		'ElementType' => 'Varchar',
		'Sort' => 'Int',
	);

	private static $has_one = array(
		'Skeleton' => Skeleton::class
	);

	private static $table_name = 'ElementSkeletonPart';

	private static $summary_fields = array(
		'ElementName'
	);

	private static $field_labels = array(
		'ElementName' => 'Element Name'
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$pageType = $this->Skeleton()->PageType;
		$elementTypes = ElementalAreasExtension::get_available_types_for_class($pageType);

		$fields->removeByName('Sort');
		$fields->removeByName('SkeletonID');
		$fields->replaceField('ElementType', $et = DropdownField::create('ElementType', 'Which element type', $elementTypes));
		$et->setEmptyString('Please choose...');
		return $fields;
	}

	public function ElementName() {
		return singleton($this->ElementType)->getElementType();
	}
}
