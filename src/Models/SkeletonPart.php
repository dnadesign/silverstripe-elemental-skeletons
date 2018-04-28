<?php

namespace DNADesign\ElementalSkeletons\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\DropdownField;
use DNADesign\Elemental\Extensions\ElementalAreasExtension;
use SilverStripe\Security\Permission;

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
		$page = new $pageType();
		$elementTypes = $page->getElementalTypes();

		$fields->removeByName('Sort');
		$fields->removeByName('SkeletonID');
		$fields->replaceField('ElementType', $et = DropdownField::create('ElementType', 'Which element type', $elementTypes));
		$et->setEmptyString('Please choose...');
		return $fields;
	}

	public function ElementName() {
		return singleton($this->ElementType)->getType();
	}

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'VIEW_SKELETONS');
    }

    public function canEdit($member = null)
    {
        return Permission::checkMember($member, 'EDIT_SKELETONS');
    }

    public function canCreate($member = null, $context = array())
    {
        return $this->canEdit($member);
    }

    public function canDelete($member = null)
    {
        return $this->canEdit($member);
    }
}
