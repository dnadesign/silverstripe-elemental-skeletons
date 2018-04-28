<?php

namespace DNADesign\ElementalSkeletons\Extensions;

use DNADesign\ElementalSkeletons\Models\Skeleton;
use DNADesign\ElementalSkeletons\Models\SkeletonPart;
use SilverStripe\ORM\DataExtension;

class SkeletonExtension extends DataExtension
{
    public function onAfterWrite()
    {
        // Ensure the skeleton is only added once (when the page is saved for the first time)
        $changedFields = $this->owner->getChangedFields();
        if (!array_key_exists('ID', $changedFields)) {
            return;
        }

        /** @var Skeleton $skeleton */
        $skeleton = Skeleton::get()->filter('PageType', $this->owner->getClassName())->first();

        if ($skeleton && $skeleton->Parts()) {
            foreach ($skeleton->Parts() as $part) {
                $elementClass = $part->ElementType;
                $element = new $elementClass;
                $this->owner->ElementalArea()->Elements()->add($element);
            }
        }
    }
}