<?php

Route::get( 'zizpic' , 'Zizpic\Admin\Http\Controllers\InventoryController@index' );
Route::group( ['prefix' => 'admin' ] , function () {
    Route::group( ['prefix' => 'inventory' ] , function () {

        /*
         * Metrics Route
         * */

        Route::bind( 'metrics' , function($value , $route) {
            return Inventory\Admin\Models\Metric::find( $value );
        } );

        Route::resource( 'metrics' , 'Inventory\Admin\Http\Controllers\MetricController' , [
            'names' => [
                'edit'    => 'metrics.edit' ,
                'show'    => 'metrics.show' ,
                'destroy' => 'metrics.destroy' ,
                'update'  => 'metrics.update' ,
                'store'   => 'metrics.store' ,
                'index'   => 'metrics' ,
                'create'  => 'metrics.create' ,
            ]
                ]
        );

        Route::bind( 'suppliers' , function($value , $route) {
            return Inventory\Admin\Models\Supplier::find( $value );
        } );

        Route::resource( 'suppliers' , 'Inventory\Admin\Http\Controllers\SupplierController' , [
            'names' => [
                'edit'    => 'suppliers.edit' ,
                'show'    => 'suppliers.show' ,
                'destroy' => 'suppliers.destroy' ,
                'update'  => 'suppliers.update' ,
                'store'   => 'suppliers.store' ,
                'index'   => 'suppliers' ,
                'create'  => 'suppliers.create' ,
            ]
                ]
        );


        Route::bind( 'locations' , function($value , $route) {
            return Inventory\Admin\Models\Location::find( $value );
        } );
        Route::get( 'inventories/locations/{location_id}/getStock' , ['as' => 'locations.getStock' , 'uses' => 'Inventory\Admin\Http\Controllers\LocationController@getStock' ] );

        Route::resource( 'locations' , 'Inventory\Admin\Http\Controllers\LocationController' , [
            'names' => [
                'edit'    => 'locations.edit' ,
                'show'    => 'locations.show' ,
                'destroy' => 'locations.destroy' ,
                'update'  => 'locations.update' ,
                'store'   => 'locations.store' ,
                'index'   => 'locations' ,
                'create'  => 'locations.create' ,
            ]
                ]
        );

        Route::bind( 'inventories' , function($value , $route) {
            return Inventory\Admin\Models\Inventory::find( $value );
        } );


        Route::resource( 'inventories' , 'Inventory\Admin\Http\Controllers\InventoryController' , [
            'names' => [
                'edit'    => 'inventories.edit' ,
                'show'    => 'inventories.show' ,
                'destroy' => 'inventories.destroy' ,
                'update'  => 'inventories.update' ,
                'store'   => 'inventories.store' ,
                'index'   => 'inventories' ,
                'create'  => 'inventories.create' ,
            ]
                ]
        );

        Route::bind( 'stocks' , function($value , $route) {
            return Inventory\Admin\Models\InventoryStock::find( $value );
        } );
        Route::bind( 'stocks.kit_edit' , function($value , $route) {
            return Inventory\Admin\Models\InventoryStock::find( $value );
        } );
        /* Route::get( 'inventories/{inventory_id}/stocks/{stock_id}/split' , 'Inventory\Admin\Http\Controllers\InventoryStockController@split' );
          Route::post( 'inventories.stocks/split' , 'Inventory\Admin\Http\Controllers\InventoryStockController@split' ); */
        Route::get( 'inventories/{inventory_id}/get_stocks_for_transaction' , ['as' => 'get_stocks_for_transaction' , 'uses' => 'Inventory\Admin\Http\Controllers\InventoryStockController@get_stocks_for_transaction' ] );
        Route::get( 'locations/{location_id}/get_stocks_for_transaction_by_location' , ['as' => 'get_stocks_for_transaction_by_location' , 'uses' => 'Inventory\Admin\Http\Controllers\InventoryStockController@get_stocks_for_transaction_by_location' ] );

        Route::resource( 'inventories.stocks' , 'Inventory\Admin\Http\Controllers\InventoryStockController' , [
            'names' => [
                'edit'    => 'stocks.edit' ,
                'show'    => 'stocks.show' ,
                'destroy' => 'stocks.destroy' ,
                'update'  => 'stocks.update' ,
                'store'   => 'stocks.store' ,
                'index'   => 'stocks' ,
                'create'  => 'stocks.create' ,
            ]
                ]
        );
        Route::get( 'inventories/{inventory_id}/stocks/{stock_id}/kit_edit' , ['as' => 'stocks.kit_edit' , 'uses' => 'Inventory\Admin\Http\Controllers\InventoryStockController@kit_edit' ] );
        Route::post( 'inventories/{inventory_id}/stocks/{stock_id}/kit_update' , ['as' => 'stocks.kit_update' , 'uses' => 'Inventory\Admin\Http\Controllers\InventoryStockController@kit_update' ] );


// kits route

        Route::bind( 'transactionstates' , function($value , $route) {
            return Inventory\Admin\Models\InventoryTransactionState::find( $value );
        } );


        Route::resource( 'transactionstates' , 'Inventory\Admin\Http\Controllers\InventoryTransactionStateController' , [
            'names' => [
                'edit'    => 'transactionstates.edit' ,
                'show'    => 'transactionstates.show' ,
                'destroy' => 'transactionstates.destroy' ,
                'update'  => 'transactionstates.update' ,
                'store'   => 'transactionstates.store' ,
                'index'   => 'transactionstates' ,
                'create'  => 'transactionstates.create'
            ]
                ]
        );

        Route::resource( 'kits' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@index' );


        Route::bind( 'transactions' , function($value , $route) {
            return Inventory\Admin\Models\InventoryTransactionState::find( $value );
        } );

        Route::post( 'transactions/create' , ['as' => 'transactions.create' , 'uses' => 'Inventory\Admin\Http\Controllers\InventoryTransactionsController@create' ] );

        Route::get( 'transactions/{bulk_id}/download_pdf' , ['as' => 'transactions.download_pdf' , 'uses' => 'Inventory\Admin\Http\Controllers\InventoryTransactionsController@download_pdf' ] );

        Route::resource( 'transactions' , 'Inventory\Admin\Http\Controllers\InventoryTransactionsController' , [
            'names' => [
                'show'  => 'transactions.show' ,
                'store' => 'transactions.store' ,
                'index' => 'transactions' ,
            ] ,
            'only'  => [
                'store' ,
                'show' ,
                'index' ,
            ]
                ]
        );
// Location
        Route::post( 'location/transactions/create' , ['as' => 'transactions.create' , 'uses' => 'Inventory\Admin\Http\Controllers\InventoryTransactionsController@create' ] );
        Route::get( 'location/transactions/{bulk_id}/download_pdf' , ['as' => 'transactions.download_pdf' , 'uses' => 'Inventory\Admin\Http\Controllers\InventoryTransactionsController@download_pdf' ] );


        Route::post( 'inventory/stocks/split' , 'Inventory\Admin\Http\Controllers\InventoryStockController@split' );



        /* ------- Kits CRUD Route ---------- */
        Route::resource( 'inventory/kits' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@index' );
        Route::post( 'inventory/kits/store' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@store' );
        Route::resource( 'inventory/kits/create-kits' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@create' );
        Route::resource( 'inventory/kits/create' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@create' );

        Route::resource( 'inventory/kits/create' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@create' );
        Route::get( 'inventory/kits/edit/{id}' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@edit' );
        Route::post( 'inventory/kits/update/{id}' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@update' );
        Route::get( 'inventory/kits/show/{id}' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@show' );
        Route::get( 'inventory/kits/destroy/{id}' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@destroy' );


        Route::post( 'inventory/kits/storekitstocks' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@storekitstocks' );
        Route::resource( 'inventory/kits/create-kitstocks' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@createkitstocks' );
        Route::get( 'inventory/kits/editkitstocks/{id}' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@editkitstocks' );
        Route::post( 'inventory/kits/updatekitstocks/{id}' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@updatekitstocks' );
        Route::get( 'inventory/kits/destroykitstocks/{id}' , 'Inventory\Admin\Http\Controllers\InventoryKitsController@destroykitstocks' );


        Route::get( 'reports/inventoryStock' , ['as' => 'inventoryStock' , 'uses' => 'Inventory\Admin\Http\Controllers\InventoryReportController@inventoryStockByLocation' ] );

        /* ------- Kits stocks CRUD Route ---------- */
        Route::get( 'inventory/kitstocks' , 'Inventory\Admin\Http\Controllers\InventorykitstocksController@index' );
        Route::post( 'inventory/kitstocks/store' , 'Inventory\Admin\Http\Controllers\InventorykitstocksController@store' );
        Route::get( 'inventory/kitstocks/create-kitstocks' , 'Inventory\Admin\Http\Controllers\InventorykitstocksController@createkitstocks' );
        Route::resource( 'inventory/kitstocks/create-kitstocks' , 'Inventory\Admin\Http\Controllers\InventorykitstocksController@createkitstocks' );
        Route::get( 'inventory/kitstocks/edit/{id}' , 'Inventory\Admin\Http\Controllers\InventorykitstocksController@edit' );
        Route::post( 'inventory/kitstocks/update/{id}' , 'Inventory\Admin\Http\Controllers\InventorykitstocksController@update' );
        Route::get( 'inventory/kitstocks/show/{id}' , 'Inventory\Admin\Http\Controllers\InventorykitstocksController@show' );
        Route::get( 'inventory/kitstocks/destroy/{id}' , 'Inventory\Admin\Http\Controllers\InventorykitstocksController@destroy' );
    } );
} );
