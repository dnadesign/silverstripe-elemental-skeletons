<?php

namespace DNADesign\ElementalSkeletons\Tests\Models;

use DNADesign\Elemental\Extensions\ElementalAreasExtension;
use DNADesign\Elemental\Tests\Src\TestPage;
use DNADesign\ElementalSkeletons\Models\Skeleton;
use \Page;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\ORM\DataObject;

class SkeletonTest extends SapphireTest
{
    protected static $required_extensions = [
        Page::class => [
            ElementalAreasExtension::class
        ]
    ];

    public function testGetDecoratedBy()
    {
        $skeleton = new Skeleton();

        // getDecoratedBy should only return sub-classes of the second argument, it should not include the base class
        $siteTreeClasses = $skeleton->getDecoratedBy(ElementalAreasExtension::class, SiteTree::class);
        $this->assertArrayHasKey(Page::class, $siteTreeClasses);
        $this->assertArrayNotHasKey(SiteTree::class, $siteTreeClasses);

        $pageClasses = $skeleton->getDecoratedBy(ElementalAreasExtension::class, Page::class);
        $this->assertArrayHasKey(Page::class, $pageClasses);
        $this->assertArrayNotHasKey(SiteTree::class, $pageClasses);
        
        $classes = $skeleton->getDecoratedBy(ElementalAreasExtension::class, DataObject::class);
        $this->assertArrayHasKey(Page::class, $classes);
        $this->assertArrayNotHasKey(SiteTree::class, $classes);
    }
}