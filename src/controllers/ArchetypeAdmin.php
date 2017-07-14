<?php

namespace DNADesign\ElementalArchetypes\Controllers;

use SilverStripe\Admin\ModelAdmin;

/**
 * @package elemental
 */
class SkeletonAdmin extends ModelAdmin {

    private static $managed_models = array(
        'DNADesign\ElementalSkeletons\Models\Skeleton'
    );

    private static $menu_title = 'Element Skeletons';

    private static $url_segment = 'elemental-skeletons';

}
