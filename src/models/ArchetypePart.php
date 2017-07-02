<?php

namespace DNADesign\ElementalArchetypes\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\DropdownField;

/**
 * Creates a archetype of elements that can be used as a template that is defined
 * within the CMS
 */
class ArchetypePart extends DataObject {

	private static $db = array(
		'ElementType' => 'Varchar',
		'Sort' => 'Int',
	);

	private static $has_one = array(
		'Archetype' => Archetype::class
	);

	private static $table_name = 'ElementArchetypePart';

	private static $summary_fields = array(
		'ElementName'
	);

	private static $field_labels = array(
		'ElementName' => 'Element Name'
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$pageType = $this->Archetype()->PageType;
		$elementTypes = singleton($pageType)->getAvailableTypes();

		$fields->removeByName('Sort');
		$fields->removeByName('ArchetypeID');
		$fields->replaceField('ElementType', $et = DropdownField::create('ElementType', 'Which element type', $elementTypes));
		$et->setEmptyString('Please choose...');
		return $fields;
	}

	public function ElementName() {
		return singleton($this->ElementType)->getElementType();
	}
}
