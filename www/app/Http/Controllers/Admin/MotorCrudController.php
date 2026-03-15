<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MotorRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MotorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MotorCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Motor::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/motor');
        CRUD::setEntityNameStrings('motor', 'motors');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn('name');

        $this->crud->addColumn([
        'name'      => 'fuel_type_id',
        'label'     => 'Fuel type',
        'type'      => 'select',
        'entity'    => 'FuelType', // Relationship method in the model
        'attribute' => 'name', // Display column in related table
        'model'     => \App\Models\FuelType::class
        ]);

        $this->crud->addColumn('price');
        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MotorRequest::class);

        $this->crud->addField([
            'type'  => 'text',
            'name'  => 'name',
            'label' => 'Name',
        ]);

        $this->crud->addField([
            'type'  => 'select',
            'name'  => 'fuel_type_id',
            'label' => 'Fuel type',
            'entity' => 'FuelType',
            'attribute' => 'name',
            'model' => \App\Models\FuelType::class
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
