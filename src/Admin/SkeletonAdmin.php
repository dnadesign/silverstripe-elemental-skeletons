<?php

namespace DNADesign\ElementalSkeletons\Admin;

use DNADesign\ElementalSkeletons\Models\Skeleton;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Security\Permission;

/**
 * @package elemental
 */
class SkeletonAdmin extends ModelAdmin {

    private static $managed_models = array(
        Skeleton::class
    );

    private static $menu_title = 'Skeletons';

    private static $url_segment = 'skeletons';

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'VIEW_SKELETONS');
    }

}
