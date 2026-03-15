<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CarmotorRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CarmotorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CarmotorCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Carmotor::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/carmotor');
        CRUD::setEntityNameStrings('carmotor', 'carmotors');
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
            'name'      => 'Car.Vendor.name',
            'label'     => 'Vendor',
            'type'      => 'text'
        ]);

        $this->crud->addColumn([
            'name'      => 'car_id',
            'label'     => 'Car',
            'type'      => 'select',
            'entity'    => 'Car', // Relationship method in the model
            'attribute' => 'name', // Display column in related table
            'model'     => \App\Models\Car::class
        ]);

        $this->crud->addColumn([
            'name'      => 'Motor.FuelType.name',
            'label'     => 'Fuel type',
            'type'      => 'text'
        ]);
        $this->crud->addColumn([
            'name'      => 'motor_id',
            'label'     => 'Motor',
            'type'      => 'select',
            'entity'    => 'Motor', // Relationship method in the model
            'attribute' => 'name', // Display column in related table
            'model'     => \App\Models\Motor::class
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CarmotorRequest::class);

        $this->crud->addField([
            'type'  => 'select_grouped',
            'name'  => 'car_id',
            'label' => 'Car',
            'entity' => 'Car',
            'attribute' => 'name',
            'model' => \App\Models\Car::class,
            'group_by'  => 'Vendor',
            'group_by_attribute' => 'name',
            'group_by_relationship_back' => 'Cars',
        ]);

        $this->crud->addField([
            'type'  => 'select_grouped',
            'name'  => 'motor_id',
            'label' => 'Motor',
            'entity' => 'Motor',
            'attribute' => 'name',
            'model' => \App\Models\Motor::class,
            'group_by'  => 'FuelType',
            'group_by_attribute' => 'name',
            'group_by_relationship_back' => 'Motors'
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
