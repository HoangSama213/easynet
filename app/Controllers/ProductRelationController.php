<?php

namespace App\Controllers;

use App\Models\ProductRelation;
use App\Models\SupplierProduct;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class ProductRelationController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $model = new ProductRelation();
        $selectedId = max(0, (int) $this->request->input('chi_tiet_id', 0));
        $selectedProduct = $selectedId > 0 ? $model->findProduct($selectedId) : null;
        $crossSellRows = $selectedProduct ? $model->getRelations((int) $selectedProduct['id'], 'cross_sell') : [];
        $upSellRows = $selectedProduct ? $model->getRelations((int) $selectedProduct['id'], 'up_sell') : [];

        $this->view('product_relations.list', [
            'title' => 'Quan hệ sản phẩm',
            'selectedProduct' => $selectedProduct,
            'crossSellRows' => $crossSellRows,
            'upSellRows' => $upSellRows,
            'extraCssFiles' => ['assets/css/quan-he-san-pham.css'],
            'extraJsFiles' => ['assets/js/product-relations.js'],
            'topbarButton' => function_exists('is_editor') && is_editor()
                ? [
                    'label' => 'Quản lý',
                    'url' => base_url('quan-he-san-pham/quan-ly' . ($selectedProduct ? '?chi_tiet_id=' . (int) $selectedProduct['id'] : '')),
                    'ariaLabel' => 'Mở trang quản lý quan hệ sản phẩm',
                ]
                : [],
        ]);
    }

    public function manage(): void
    {
        $this->requireEditor();

        $model = new ProductRelation();
        $selectedId = max(0, (int) $this->request->input('chi_tiet_id', 0));
        $defaultType = (string) $this->request->input('type', 'cross_sell');
        if (!in_array($defaultType, ['cross_sell', 'up_sell'], true)) {
            $defaultType = 'cross_sell';
        }

        $selectedProduct = $selectedId > 0 ? $model->findProduct($selectedId) : null;
        $crossSellRows = $selectedProduct ? $model->getRelations((int) $selectedProduct['id'], 'cross_sell') : [];
        $upSellRows = $selectedProduct ? $model->getRelations((int) $selectedProduct['id'], 'up_sell') : [];

        $this->view('product_relations.manage', [
            'title' => 'Quản lý quan hệ sản phẩm',
            'selectedProduct' => $selectedProduct,
            'crossSellRows' => $crossSellRows,
            'upSellRows' => $upSellRows,
            'defaultType' => $defaultType,
            'extraCssFiles' => ['assets/css/quan-he-san-pham.css'],
            'extraJsFiles' => ['assets/js/product-relations.js'],
        ]);
    }

    public function api(): void
    {
        $this->requireAuth();
        header('Content-Type: application/json; charset=UTF-8');

        $model = new ProductRelation();

        try {
            $action = trim((string) $this->request->input('action', ''));

            if ($this->request->method() === 'GET') {
                if ($action === 'search') {
                    $keyword = trim((string) $this->request->input('q', ''));
                    $categoryId = max(0, (int) $this->request->input('category_id', 0));
                    $excludeId = max(0, (int) $this->request->input('exclude_id', 0));

                    $this->jsonResponse([
                        'success' => true,
                        'data' => $model->searchProducts($keyword, $categoryId, 10, $excludeId > 0 ? $excludeId : null),
                    ]);
                }

                if ($action === 'get_relations') {
                    $chiTietId = max(0, (int) $this->request->input('chi_tiet_id', 0));
                    $type = trim((string) $this->request->input('type', ''));

                    if ($chiTietId <= 0) {
                        throw new InvalidArgumentException('Thiếu sản phẩm gốc.');
                    }

                    $this->jsonResponse([
                        'success' => true,
                        'data' => $model->getRelations($chiTietId, $type),
                    ]);
                }

                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Action không hợp lệ.',
                ], 400);
            }

            $this->assertApiCsrf();

            if (!is_editor()) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Bạn không có quyền thực hiện thao tác này.',
                ], 403);
            }

            if ($action === 'add') {
                $sourceId = max(0, (int) $this->request->input('source_chi_tiet_id', 0));
                $targetId = max(0, (int) $this->request->input('target_chi_tiet_id', 0));
                $type = trim((string) $this->request->input('relation_type', ''));
                $giaMuaKem = trim((string) $this->request->input('gia_mua_kem', ''));
                $soLuongToiDa = max(1, (int) $this->request->input('so_luong_toi_da', 1));
                $note = trim((string) $this->request->input('note', ''));
                $chiKichHoat = (int) $this->request->input('chi_kich_hoat_khi_con_hang', 0) === 1;

                $row = $model->addRelation(
                    $sourceId,
                    $targetId,
                    $type,
                    $giaMuaKem !== '' ? (float) $giaMuaKem : null,
                    $soLuongToiDa,
                    $note !== '' ? $note : null,
                    $chiKichHoat
                );

                $this->jsonResponse([
                    'success' => true,
                    'data' => $row,
                ]);
            }

            if ($action === 'delete') {
                $id = max(0, (int) $this->request->input('id', 0));
                if ($id <= 0) {
                    throw new InvalidArgumentException('Thiếu mã quan hệ.');
                }

                $model->deleteRelation($id);
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Đã xóa quan hệ sản phẩm.',
                ]);
            }

            if ($action === 'update_note') {
                $id = max(0, (int) $this->request->input('id', 0));
                if ($id <= 0) {
                    throw new InvalidArgumentException('Thiếu mã quan hệ.');
                }

                $model->updateNote($id, (string) $this->request->input('note', ''));
                $this->jsonResponse([
                    'success' => true,
                    'data' => $model->findRelationById($id),
                ]);
            }

            if ($action === 'update_cross_sell') {
                $id = max(0, (int) $this->request->input('id', 0));
                if ($id <= 0) {
                    throw new InvalidArgumentException('Thiếu mã quan hệ.');
                }

                $row = $model->updateCrossSell(
                    $id,
                    trim((string) $this->request->input('gia_mua_kem', '')) !== '' ? (float) $this->request->input('gia_mua_kem') : null,
                    max(1, (int) $this->request->input('so_luong_toi_da', 1))
                );

                $this->jsonResponse([
                    'success' => true,
                    'data' => $row,
                ]);
            }

            if ($action === 'update_up_sell') {
                $id = max(0, (int) $this->request->input('id', 0));
                if ($id <= 0) {
                    throw new InvalidArgumentException('Thiếu mã quan hệ.');
                }

                $row = $model->updateUpSell($id, (int) $this->request->input('chi_kich_hoat_khi_con_hang', 0) === 1);
                $this->jsonResponse([
                    'success' => true,
                    'data' => $row,
                ]);
            }

            $this->jsonResponse([
                'success' => false,
                'message' => 'Action không hợp lệ.',
            ], 400);
        } catch (InvalidArgumentException | RuntimeException $exception) {
            $this->jsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        } catch (Throwable) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Không thể xử lý yêu cầu lúc này.',
            ], 500);
        }
    }

    public function legacyIndexRedirect(): void
    {
        redirect('quan-he-san-pham');
    }

    public function legacyShowRedirect(string $id): void
    {
        $product = (new SupplierProduct())->findById((int) $id);

        if ($product === null) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        redirect('quan-he-san-pham/quan-ly?chi_tiet_id=' . (int) $product['chi_tiet_id']);
    }

    public function legacyUpdateRedirect(string $id): void
    {
        $product = (new SupplierProduct())->findById((int) $id);

        if ($product === null) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        session_flash('error', 'Trang quản lý quan hệ sản phẩm đã chuyển sang module mới.');
        redirect('quan-he-san-pham/quan-ly?chi_tiet_id=' . (int) $product['chi_tiet_id']);
    }

    private function assertApiCsrf(): void
    {
        if (!hash_equals(csrf_token(), (string) $this->request->input('_token', ''))) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Phiên làm việc không hợp lệ.',
            ], 419);
        }
    }

    private function jsonResponse(array $payload, int $status = 200): never
    {
        http_response_code($status);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
