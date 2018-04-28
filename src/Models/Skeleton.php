<?php

namespace DNADesign\ElementalSkeletons\Models;

use DNADesign\Elemental\Extensions\ElementalAreasExtension;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Extensible;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

use Page;

/**
 * Creates a skeleton of elements that can help bootstrap pages with blank elements.
 *
 * A Skeleton consists of a title (visible only in the CMS) and a link to a page type. Each page type can be assigned
 * only one {@link Skeleton} object. Each skeleton contains one or more {@link SkeletonPart} objects.
 *
 * Whenever any new page is created, the list of {@link Skeleton} objects are checked to see if there is one that 
 * matches the page type that was created. If one is found, any linked {@link SkeletonPart} are instantiated and added 
 * to the newly-created page.
 */
class Skeleton extends DataObject implements PermissionProvider {

	private static $db = array(
		'Title' => 'Varchar',
		'PageType' => 'Varchar'
	);

	private static $has_many = array(
		'Parts' => SkeletonPart::class
	);

	private static $table_name = 'ElementSkeletons';

	private static $summary_fields = array(
		'Title',
		'PageTypeName'
	);

	private static $field_labels = array(
		'PageTypeName' => 'Page Type'
	);


    /**
     * Get all classes that have the given extension, that don't already have a {@link Skeleton} associated with them.
     *
     * If the current {@link Skeleton} has a page type set already, that is included in the list so it can remain saved.
     *
     * @param string $extension The extension to look for
     * @param string $baseClass The base class to search for extensions on
     * @return array All classes that are decorated by the given extension
     */
	public function getDecoratedBy($extension, $baseClass) {
		$classes = array();
		$existingSkeletons = Skeleton::get();

		foreach (ClassInfo::subClassesFor($baseClass) as $className) {
			$skeletonExistsForClass = !is_null($existingSkeletons->filter('PageType', $className)->first());

		    if (Extensible::has_extension($className, $extension)) {
				if ($this->PageType == $className || !$skeletonExistsForClass) {
                    $classes[$className] = singleton($className)->singular_name();
                }
			}
		}

		return $classes;
	}

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$pageTypes = $this->getDecoratedBy(ElementalAreasExtension::class, Page::class);
		$fields->removeByName('Sort');
		$fields->replaceField('PageType', $pt = DropdownField::create('PageType', 'Which page type to use as the base', $pageTypes));
		$pt->setEmptyString('Please choose...');
		$pt->setRightTitle('This will determine which elements are possible to add to the skeleton');
		if ($this->isinDB()) {
			$gf = $fields->fieldByName('Root.Parts.Parts');
			$gfc = $gf->getConfig();
			$gfc->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
			$gfc->addComponent(new GridFieldOrderableRows('Sort'));
			$fields->removeByName('Parts');
			$fields->addFieldToTab('Root.Main', $gf);
		}
		return $fields;
	}

	public function PageTypeName() {
		if ($this->PageType) {
            return singleton($this->PageType)->singular_name();
        } else {
		    return '';
        }
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

    public function providePermissions()
    {
        return [
            'VIEW_SKELETONS' => [
                'name' => 'View all elemental skeletons',
                'category' => 'Elemental',
                'help' => 'Allow viewing the skeletons for new pages, but do not allow editing of these skeletons',
                'sort' => 100
            ],
            'EDIT_SKELETONS' => [
                'name' => 'Edit all elemental skeletons',
                'category' => 'Elemental',
                'help' => 'Allow creation of new skeletons, as well as editing and deletion of existing skeletons',
                'sort' => 200
            ]
        ];
    }
}
