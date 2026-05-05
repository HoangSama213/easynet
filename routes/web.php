<?php

 
 use App\Controllers\AuthController;
use App\Controllers\CustomerController;
use App\Controllers\DashboardController;
use App\Controllers\ElectromechanicalLightCurrentController;
use App\Controllers\IctInfrastructureController;
use App\Controllers\ItemController;
use App\Controllers\PartnerController;
use App\Controllers\ProductRelationController;
use App\Controllers\SmartSolutionController;
use App\Controllers\SupplierProductController;
use App\Controllers\ThongBaoController;
use App\Controllers\ComboController;
use App\Core\Router;

/** @var Router $router */
$router->get('/', [DashboardController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);
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
$router->get('/supplier-products/history', [SupplierProductController::class, 'historyIndex']);
$router->get('/supplier-products/media', [SupplierProductController::class, 'mediaIndex']);
$router->post('/supplier-products/store', [SupplierProductController::class, 'store']);
$router->get('/supplier-products/{id}/media', [SupplierProductController::class, 'mediaShow']);
$router->post('/supplier-products/{id}/media/update', [SupplierProductController::class, 'mediaUpdate']);
$router->get('/supplier-products/{id}', [SupplierProductController::class, 'show']);
$router->post('/supplier-products/{id}/update', [SupplierProductController::class, 'update']);
$router->post('/supplier-products/{id}/delete', [SupplierProductController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| Tạm khóa module Quan hệ sản phẩm cho đến khi có yêu cầu mở lại
|--------------------------------------------------------------------------
|
| Các route dưới đây được comment để ẩn toàn bộ trang Quan hệ sản phẩm
| và các đường dẫn alias cũ khỏi hệ thống trong giai đoạn hiện tại.
|
$router->get('/supplier-products/relations', [ProductRelationController::class, 'legacyIndexRedirect']);
$router->get('/supplier-products/{id}/relations', [ProductRelationController::class, 'legacyShowRedirect']);
$router->post('/supplier-products/{id}/relations/update', [ProductRelationController::class, 'legacyUpdateRedirect']);
$router->get('/quan-he-san-pham', [ProductRelationController::class, 'index']);
$router->get('/quan-he-san-pham/quan-ly', [ProductRelationController::class, 'manage']);
$router->get('/quan-he-san-pham/api', [ProductRelationController::class, 'api']);
$router->post('/quan-he-san-pham/api', [ProductRelationController::class, 'api']);
*/

/*
|--------------------------------------------------------------------------
| Tạm khóa module Combo cho đến khi có yêu cầu mở lại
|--------------------------------------------------------------------------
|
| Các route dưới đây được comment để ẩn toàn bộ trang Combo và
| Chiến dịch marketing khỏi hệ thống trong giai đoạn hiện tại.
|
$router->get('/combos', [ComboController::class, 'index']);
$router->get('/combos/create', [ComboController::class, 'create']);
$router->get('/combos/campaigns', [ComboController::class, 'campaigns']);
$router->post('/combos/campaigns/store', [ComboController::class, 'campaignStore']);
$router->post('/combos/campaigns/{id}/update', [ComboController::class, 'campaignUpdate']);
$router->post('/combos/campaigns/{id}/delete', [ComboController::class, 'campaignDestroy']);
$router->post('/combos/store', [ComboController::class, 'store']);
$router->get('/combos/analytics', [ComboController::class, 'analytics']);
*/
/*
$router->get('/sales', [ComboController::class, 'sales']);
*/
$router->get('/thong-bao', [ThongBaoController::class, 'index']);
$router->get('/thong-bao/xem/{id}', [ThongBaoController::class, 'markRead']);
$router->post('/thong-bao/danh-dau-da-doc', [ThongBaoController::class, 'markAllRead']);
/*
$router->get('/combos/{id}', [ComboController::class, 'show']);
$router->get('/combos/{id}/proposal', [ComboController::class, 'proposal']);
$router->post('/combos/{id}/update', [ComboController::class, 'update']);
$router->post('/combos/{id}/delete', [ComboController::class, 'destroy']);
*/
