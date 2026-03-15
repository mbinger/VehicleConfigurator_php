<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CarRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CarCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CarCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Car::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/car');
        CRUD::setEntityNameStrings('car', 'cars');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name'      => 'vendor_id',
            'label'     => 'Vendor',
            'type'      => 'select',
            'entity'    => 'Vendor', // Relationship method in the model
            'attribute' => 'name', // Display column in related table
            'model'     => \App\Models\Vendor::class
        ]);

        $this->crud->addColumn('name');

        $this->crud->addColumn('price');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CarRequest::class);

        $this->crud->addField([
            'type'  => 'select',
            'name'  => 'vendor_id',
            'label' => 'Vendor',
            'entity' => 'Vendor',
            'attribute' => 'name',
            'model' => \App\Models\Vendor::class
        ]);

        $this->crud->addField([
            'type'  => 'text',
            'name'  => 'name',
            'label' => 'Name',
        ]);

        $this->crud->addField([
            'type'  => 'text',
            'name'  => 'price',
            'label' => 'Price',
        ]);

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
