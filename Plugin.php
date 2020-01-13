<?php namespace Depcore\CategoriesAddon;

use Backend;
use System\Classes\PluginBase;
use RainLab\Blog\Models\Category as CategoryModel;
use RainLab\Blog\Controllers\Categories as CategoriesController;

/**
 * CategoriesAddon Plugin Information File
 */
class Plugin extends PluginBase
{

    public $require = [
        'RainLab.Blog',
    ];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'depcore.categoriesaddon::lang.plugin.name',
            'description' => 'depcore.categoriesaddon::lang.plugin.description',
            'author'      => 'Depcore',
            'icon'        => 'icon-leaf'
        ];
    }


    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        $this->extendCategoriesController();
        $this->extendCategoryModel();
    }

    /**
     * Add featured image to category
     *
     * @return void
     * @author Adam
     **/
    private function extendCategoriesController()
    {
        CategoriesController::extendFormFields(function ($form, $model) {
            if (!$model instanceof CategoryModel) {
                return;
            }
            $this->addFeaturedImageField( $form );
        });

    }


    /**
     * add featured image to blog category page
     *
     * @return void
     * @author
     **/
    private function extendCategoryModel()
    {

        CategoryModel::extend(function ($model) {

            $model->addDynamicMethod('getFeaturedImage', function() use ($model) {
                return $model->featuredImage;
            });

            $model->attachOne = [
                'featuredImage' => 'System\Models\File',
            ];

        });
    }

    private function addFeaturedImageField( $form ){
        $form->addSecondaryTabFields([
            'featuredImage' => [
                'label' => 'depcore.categoriesaddon::lang.category.featured_image',
                'type' => 'fileupload',
                'mode' => 'image',

            ],

        ]);
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'depcore.categoriesaddon.some_permission' => [
                'tab' => 'depcore.categoriesaddon::lang.plugin.name',
                'label' => 'depcore.categoriesaddon::lang.permissions.some_permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'categoriesaddon' => [
                'label'       => 'depcore.categoriesaddon::lang.plugin.name',
                'url'         => Backend::url('depcore/categoriesaddon/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['depcore.categoriesaddon.*'],
                'order'       => 500,
            ],
        ];
    }

}
