<?php

use App\Controllers\AuthController;
use App\Controllers\CustomerController;
use App\Controllers\DashboardController;
use App\Controllers\ElectromechanicalLightCurrentController;
use App\Controllers\IctInfrastructureController;
use App\Controllers\ItemController;
use App\Controllers\PartnerController;
use App\Controllers\SmartSolutionController;
use App\Controllers\SupplierProductController;
use App\Core\Router;

/** @var Router $router */
$router->get('/', [DashboardController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/items', [ItemController::class, 'index']);
$router->get('/items/create', [ItemController::class, 'create']);
$router->post('/items/store', [ItemController::class, 'store']);
$router->get('/items/edit/{id}', [ItemController::class, 'edit']);
$router->post('/items/update/{id}', [ItemController::class, 'update']);
$router->post('/items/delete/{id}', [ItemController::class, 'destroy']);

$router->get('/co-dien-dien-nhe', [ElectromechanicalLightCurrentController::class, 'index']);
$router->get('/co-dien-dien-nhe/create', [ElectromechanicalLightCurrentController::class, 'create']);
$router->post('/co-dien-dien-nhe/store', [ElectromechanicalLightCurrentController::class, 'store']);
$router->get('/co-dien-dien-nhe/edit/{table}/{id}', [ElectromechanicalLightCurrentController::class, 'edit']);
$router->post('/co-dien-dien-nhe/update/{table}/{id}', [ElectromechanicalLightCurrentController::class, 'update']);
$router->post('/co-dien-dien-nhe/delete/{table}/{id}', [ElectromechanicalLightCurrentController::class, 'destroy']);

$router->get('/ha-tang-ict', [IctInfrastructureController::class, 'index']);
$router->get('/ha-tang-ict/create', [IctInfrastructureController::class, 'create']);
$router->post('/ha-tang-ict/store', [IctInfrastructureController::class, 'store']);
$router->get('/ha-tang-ict/edit/{table}/{id}', [IctInfrastructureController::class, 'edit']);
$router->post('/ha-tang-ict/update/{table}/{id}', [IctInfrastructureController::class, 'update']);
$router->post('/ha-tang-ict/delete/{table}/{id}', [IctInfrastructureController::class, 'destroy']);

$router->get('/smart-solution', [SmartSolutionController::class, 'index']);
$router->get('/smart-solution/create', [SmartSolutionController::class, 'create']);
$router->post('/smart-solution/store', [SmartSolutionController::class, 'store']);
$router->get('/smart-solution/edit/{table}/{id}', [SmartSolutionController::class, 'edit']);
$router->post('/smart-solution/update/{table}/{id}', [SmartSolutionController::class, 'update']);
$router->post('/smart-solution/delete/{table}/{id}', [SmartSolutionController::class, 'destroy']);

$router->get('/doi-tac', [PartnerController::class, 'index']);
$router->get('/doi-tac/create', [PartnerController::class, 'create']);
$router->post('/doi-tac/store', [PartnerController::class, 'store']);
$router->get('/doi-tac/edit/{id}', [PartnerController::class, 'edit']);
$router->post('/doi-tac/update/{id}', [PartnerController::class, 'update']);
$router->post('/doi-tac/delete/{id}', [PartnerController::class, 'destroy']);

$router->get('/khach-hang', [CustomerController::class, 'index']);
$router->get('/khach-hang/create', [CustomerController::class, 'create']);
$router->post('/khach-hang/store', [CustomerController::class, 'store']);
$router->get('/khach-hang/edit/{id}', [CustomerController::class, 'edit']);
$router->post('/khach-hang/update/{id}', [CustomerController::class, 'update']);
$router->post('/khach-hang/delete/{id}', [CustomerController::class, 'destroy']);

$router->get('/supplier-products', [SupplierProductController::class, 'index']);
$router->get('/supplier-products/create', [SupplierProductController::class, 'create']);
$router->post('/supplier-products/store', [SupplierProductController::class, 'store']);
$router->get('/supplier-products/{nccId}/{chiTietId}', [SupplierProductController::class, 'show']);
$router->post('/supplier-products/{nccId}/{chiTietId}/update', [SupplierProductController::class, 'update']);
