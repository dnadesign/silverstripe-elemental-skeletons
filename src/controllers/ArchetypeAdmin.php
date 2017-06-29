<?php

namespace DNADesign\ElementalArchetypes\Controllers;

use SilverStripe\Admin\ModelAdmin;

/**
 * @package elemental
 */
class ArchetypeAdmin extends ModelAdmin {

    private static $managed_models = array(
        'SilverStripe\ElementalArchetypes\Models\Archetype'
    );

    private static $menu_title = 'Element Archetypes';

    private static $url_segment = 'elemental-archetypes';

}
