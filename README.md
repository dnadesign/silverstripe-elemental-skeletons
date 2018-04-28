# Elemental Skeletons

Creates a skeleton of elements that can be used as a template when creating new pages.

## Installation

Installation occurs via `composer`:

```bash
composer require dnadesign/silverstripe-elemental-skeletons
```

## Configuration

This module includes two new permissions - `View all elemental skeletons` and `Edit all elemental skeletons`. These can 
be used to limit access to the `Skeletons` section of the CMS. Once the correct permission is given, skeletons can be 
created in the CMS.

After creating skeletons and assigning them to a particular page type, whenever a new page of that type is created, the 
appropriate skeleton will be created as well. Currently, this only works for direct matches (in other words, creating a 
skeleton for the `Page` class will not do anything when a new `NewsArticle` page type is created).

## Module status

 * This module currently only supports elemental areas that are attached to `Page`, not any other class.
 * This module is still in a demo format. Pull requests are welcome to include workflow, ordering of skeleton parts etc.
