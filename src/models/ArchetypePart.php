<?php

namespace DNADesign\ElementalArchetypes\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\DropdownField;

/**
 * Creates a recipe of elements that can be used
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
