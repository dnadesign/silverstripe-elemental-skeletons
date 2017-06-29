<?php

namespace DNADesign\ElementalArchetypes\Models;

use DNADesign\Elemental\Extensions\ElementPageExtension;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Extensible;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\ORM\DataObject;

use Page;

/**
 * Creates a Archetype of elements that can be used to setup a page
 */
class Archetype extends DataObject {

	private static $db = array(
		'Title' => 'Varchar',
		'PageType' => 'Varchar'
	);

	private static $has_many = array(
		'Parts' => ArchetypePart::class
	);

	private static $table_name = 'ElementArchetype';

	private static $summary_fields = array(
		'Title',
		'PageTypeName'
	);

	private static $field_labels = array(
		'PageTypeName' => 'Page Type'
	);



	public static function getDecoratedBy($extension, $baseClass){
		$classes = array();

		foreach(ClassInfo::subClassesFor($baseClass) as $className) {
			if (Extensible::has_extension($className, $extension)){
				$classes[$className] = singleton($className)->singular_name();
			}
		}
		return $classes;
	}

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$pageTypes = self::getDecoratedBy(ElementPageExtension::class, Page::class);
		$fields->removeByName('Sort');
		$fields->replaceField('PageType', $pt = DropdownField::create('PageType', 'Which page type to use as the base', $pageTypes));
		$pt->setEmptyString('Please choose...');
		$pt->setRightTitle('This will determine which elements are possible to add to the recipe');
		if ($this->isinDB()) {
			$gf = $fields->fieldByName('Root.Parts.Parts');
			$gfc = $gf->getConfig();
			$gfc->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
			$gfc->addComponent(new GridFieldOrderableRows('Sort'));
			$fields->removeByName('Ingredients');
			$fields->addFieldToTab('Root.Main', $gf);

			$fields->addFieldToTab('Root.Main', new FormAction('create', 'Create new ' . $this->Title . ' page'));
		}
		return $fields;
	}

	public function PageTypeName() {
		return singleton($this->PageType)->singular_name();
	}

}