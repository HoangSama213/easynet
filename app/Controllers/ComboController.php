<?php

namespace App\Controllers;

use App\Models\Combo;
use Throwable;

class ComboController extends Controller
{
    public function index(): void
    {
        $model = new Combo();
        $page = (int) $this->request->input('page', 1);
        $keyword = trim((string) $this->request->input('keyword', ''));
        $pagination = $model->paginate($page, 10, $keyword);

        $this->view('combos.index', [
            'title' => 'Quản lý combo',
            'rows' => $pagination['items'],
            'pagination' => $pagination,
            'keyword' => $keyword,
            'topbarFilters' => [
                'action' => base_url('combos'),
                'keyword' => $keyword,
                'placeholder' => 'Tìm mã combo, tên combo...',
            ],
            'topbarButton' => [
                'label' => 'Phân tích',
                'url' => base_url('combos/analytics'),
            ],
        ]);
    }

    public function create(): void
    {
        $this->requireEditor();
        $model = new Combo();

        $this->view('combos.form', [
            'title' => 'Tạo combo mới',
            'isCreate' => true,
            'formAction' => base_url('combos/store'),
            'combo' => null,
            'productOptions' => $model->productOptions(),
            'errors' => $_SESSION['_combo_errors'] ?? [],
            'old' => $_SESSION['_combo_old'] ?? [],
        ]);

        unset($_SESSION['_combo_errors'], $_SESSION['_combo_old']);
    }

    public function store(): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $payload = $this->validatedPayload();

        if (!empty($payload['errors'])) {
            $_SESSION['_combo_errors'] = $payload['errors'];
            $_SESSION['_combo_old'] = $payload['old'];
            session_flash('error', 'Vui lòng kiểm tra lại thông tin combo.');
            redirect('combos/create');
        }

        try {
            $id = (new Combo())->create($payload['data']);
            session_flash('success', 'Đã tạo combo mới.');
            redirect('combos/' . $id);
        } catch (Throwable $e) {
            $this->logException('store', $e, $payload['data']);
            $_SESSION['_combo_old'] = $payload['old'];
            session_flash('error', 'Không thể tạo combo lúc này. Vui lòng thử lại.');
            redirect('combos/create');
        }
    }

    public function show(string $id): void
    {
        $model = new Combo();
        $combo = $model->findById((int) $id);

        if (!$combo) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy']);
            return;
        }

        $errors = $_SESSION['_combo_errors'] ?? [];
        $old = $_SESSION['_combo_old'] ?? [];
        unset($_SESSION['_combo_errors'], $_SESSION['_combo_old']);

        $this->view('combos.form', [
            'title' => 'Chi tiết combo',
            'isCreate' => false,
            'formAction' => base_url('combos/' . $combo['id'] . '/update'),
            'combo' => $combo,
            'productOptions' => $model->productOptions(),
            'errors' => $errors,
            'old' => $old,
        ]);
    }

    public function update(string $id): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new Combo();
        $combo = $model->findById((int) $id);

        if (!$combo) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy']);
            return;
        }

        $payload = $this->validatedPayload();

        if (!empty($payload['errors'])) {
            $_SESSION['_combo_errors'] = $payload['errors'];
            $_SESSION['_combo_old'] = $payload['old'];
            session_flash('error', 'Vui lòng kiểm tra lại thông tin combo.');
            redirect('combos/' . (int) $id);
        }

        try {
            $model->update((int) $id, $payload['data']);
            session_flash('success', 'Đã cập nhật combo.');
            redirect('combos/' . (int) $id);
        } catch (Throwable $e) {
            $this->logException('update', $e, ['id' => $id, 'payload' => $payload['data']]);
            $_SESSION['_combo_old'] = $payload['old'];
            session_flash('error', 'Không thể cập nhật combo lúc này. Vui lòng thử lại.');
            redirect('combos/' . (int) $id);
        }
    }

    public function destroy(string $id): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new Combo();
        $combo = $model->findById((int) $id);

        if (!$combo) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy']);
            return;
        }

        try {
            $model->delete((int) $id);
            session_flash('success', 'Đã xóa combo.');
            redirect('combos');
        } catch (Throwable $e) {
            $this->logException('delete', $e, ['id' => $id]);
            session_flash('error', 'Không thể xóa combo lúc này.');
            redirect('combos/' . (int) $id);
        }
    }

    public function proposal(string $id): void
    {
        $this->requireEditor();
        $model = new Combo();
        $combo = $model->findById((int) $id);

        if (!$combo) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy']);
            return;
        }

        $customerName = trim((string) $this->request->input('customer_name', ''));
        $customerChannel = trim((string) $this->request->input('customer_channel', ''));
        $proposal = $model->buildProposal(
            (int) $id,
            $customerName !== '' ? $customerName : null,
            $customerChannel !== '' ? $customerChannel : null
        );

        if (!empty($proposal['title']) && !empty($proposal['body']) && ($customerName !== '' || $customerChannel !== '')) {
            $model->saveProposal(
                (int) $id,
                (string) $proposal['title'],
                (string) $proposal['body'],
                $customerName !== '' ? $customerName : null,
                $customerChannel !== '' ? $customerChannel : null
            );
        }

        $this->view('combos.proposal', [
            'title' => 'Proposal combo',
            'proposal' => $proposal,
        ]);
    }

    public function analytics(): void
    {
        $model = new Combo();

        $this->view('combos.analytics', [
            'title' => 'Phân tích combo',
            'rows' => $model->analyticsSummary(),
        ]);
    }

    public function campaigns(): void
    {
        $model = new Combo();
        $keyword = trim((string) $this->request->input('keyword', ''));
        $editId = (int) $this->request->input('edit', 0);
        $editingCampaign = $editId > 0 ? $model->campaignFindById($editId) : null;

        $campaignOld = $_SESSION['_combo_campaign_old'] ?? [];
        $campaignErrors = $_SESSION['_combo_campaign_errors'] ?? [];
        unset($_SESSION['_combo_campaign_old'], $_SESSION['_combo_campaign_errors']);

        if (empty($campaignOld) && $editingCampaign) {
            $campaignOld = [
                'combo_id' => $editingCampaign['combo_id'] ?? '',
                'ten_chien_dich' => $editingCampaign['ten_chien_dich'] ?? '',
                'ghi_chu' => $editingCampaign['ghi_chu'] ?? '',
                'bat_dau' => $editingCampaign['bat_dau'] ?? '',
                'ket_thuc' => $editingCampaign['ket_thuc'] ?? '',
            ];
        }

        $this->view('combos.campaigns', [
            'title' => 'Chiến dịch marketing',
            'rows' => $model->campaignRows($keyword),
            'keyword' => $keyword,
            'comboOptions' => $model->comboOptions(),
            'editingCampaign' => $editingCampaign,
            'campaignOld' => $campaignOld,
            'campaignErrors' => $campaignErrors,
            'topbarFilters' => [
                'action' => base_url('combos/campaigns'),
                'keyword' => $keyword,
                'placeholder' => 'Tìm tên chiến dịch, mã combo hoặc tên combo...',
            ],
        ]);
    }

    public function campaignStore(): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new Combo();
        $payload = $this->validatedCampaignPayload($model);

        if (!empty($payload['errors'])) {
            $_SESSION['_combo_campaign_errors'] = $payload['errors'];
            $_SESSION['_combo_campaign_old'] = $payload['old'];
            session_flash('error', 'Vui lòng kiểm tra lại thông tin chiến dịch.');
            redirect('combos/campaigns');
        }

        try {
            $model->createCampaign($payload['data']);
            session_flash('success', 'Đã thêm chiến dịch marketing.');
            redirect('combos/campaigns');
        } catch (Throwable $e) {
            $this->logException('campaign_store', $e, $payload['data']);
            $_SESSION['_combo_campaign_old'] = $payload['old'];
            session_flash('error', 'Không thể lưu chiến dịch lúc này. Vui lòng thử lại.');
            redirect('combos/campaigns');
        }
    }

    public function campaignUpdate(string $id): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new Combo();
        $campaign = $model->campaignFindById((int) $id);

        if (!$campaign) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy']);
            return;
        }

        $payload = $this->validatedCampaignPayload($model);

        if (!empty($payload['errors'])) {
            $_SESSION['_combo_campaign_errors'] = $payload['errors'];
            $_SESSION['_combo_campaign_old'] = $payload['old'];
            session_flash('error', 'Vui lòng kiểm tra lại thông tin chiến dịch.');
            redirect('combos/campaigns?edit=' . (int) $id);
        }

        try {
            $model->updateCampaign((int) $id, $payload['data']);
            session_flash('success', 'Đã cập nhật chiến dịch marketing.');
            redirect('combos/campaigns');
        } catch (Throwable $e) {
            $this->logException('campaign_update', $e, ['id' => $id, 'payload' => $payload['data']]);
            $_SESSION['_combo_campaign_old'] = $payload['old'];
            session_flash('error', 'Không thể cập nhật chiến dịch lúc này. Vui lòng thử lại.');
            redirect('combos/campaigns?edit=' . (int) $id);
        }
    }

    public function campaignDestroy(string $id): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new Combo();
        $campaign = $model->campaignFindById((int) $id);

        if (!$campaign) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy']);
            return;
        }

        try {
            $model->deleteCampaign((int) $id);
            session_flash('success', 'Đã xóa chiến dịch marketing.');
            redirect('combos/campaigns');
        } catch (Throwable $e) {
            $this->logException('campaign_delete', $e, ['id' => $id]);
            session_flash('error', 'Không thể xóa chiến dịch lúc này.');
            redirect('combos/campaigns');
        }
    }

    public function sales(): void
    {
        $model = new Combo();
        $keyword = trim((string) $this->request->input('keyword', ''));

        $this->view('combos.sales', [
            'title' => 'Sales',
            'rows' => $model->salesRows($keyword),
            'keyword' => $keyword,
            'topbarFilters' => [
                'action' => base_url('sales'),
                'keyword' => $keyword,
                'placeholder' => 'Tìm theo nhu cầu, tên combo, chiến dịch hoặc sản phẩm...',
            ],
        ]);
    }

    private function validatedPayload(): array
    {
        $tenCombo = trim((string) $this->request->input('ten_combo', ''));
        $moTa = trim((string) $this->request->input('mo_ta', ''));
        $giaCombo = trim((string) $this->request->input('gia_combo', ''));
        $trangThai = trim((string) $this->request->input('trang_thai', 'active'));
        $itemsRaw = $this->request->input('items', []);

        $errors = [];

        if ($tenCombo === '') {
            $errors['ten_combo'] = 'Tên combo là bắt buộc.';
        }

        if ($giaCombo !== '' && !is_numeric($giaCombo)) {
            $errors['gia_combo'] = 'Giá combo phải là số hợp lệ.';
        }

        if (!in_array($trangThai, ['active', 'inactive'], true)) {
            $trangThai = 'active';
        }

        $items = [];
        foreach ((array) $itemsRaw as $item) {
            $chiTietId = (int) ($item['chi_tiet_id'] ?? 0);
            $soLuong = max(1, (int) ($item['so_luong'] ?? 1));
            if ($chiTietId > 0) {
                $items[] = ['chi_tiet_id' => $chiTietId, 'so_luong' => $soLuong];
            }
        }

        if (empty($items)) {
            $errors['items'] = 'Combo phải có ít nhất 1 sản phẩm.';
        }

        $old = [
            'ten_combo' => $tenCombo,
            'mo_ta' => $moTa,
            'gia_combo' => $giaCombo,
            'trang_thai' => $trangThai,
            'items' => $itemsRaw,
        ];

        return [
            'errors' => $errors,
            'old' => $old,
            'data' => [
                'ten_combo' => $tenCombo,
                'mo_ta' => $moTa === '' ? null : $moTa,
                'gia_combo' => $giaCombo === '' ? null : $giaCombo,
                'trang_thai' => $trangThai,
                'items' => $items,
            ],
        ];
    }

    private function validatedCampaignPayload(Combo $model): array
    {
        $comboId = (int) $this->request->input('combo_id', 0);
        $tenChienDich = trim((string) $this->request->input('ten_chien_dich', ''));
        $ghiChu = trim((string) $this->request->input('ghi_chu', ''));
        $batDau = trim((string) $this->request->input('bat_dau', ''));
        $ketThuc = trim((string) $this->request->input('ket_thuc', ''));

        $errors = [];

        if ($comboId <= 0 || !$model->comboExists($comboId)) {
            $errors['combo_id'] = 'Vui lòng chọn combo hợp lệ.';
        }

        if ($tenChienDich === '') {
            $errors['ten_chien_dich'] = 'Tên chiến dịch là bắt buộc.';
        }

        if ($batDau !== '' && $ketThuc !== '' && strtotime($batDau) !== false && strtotime($ketThuc) !== false && strtotime($batDau) > strtotime($ketThuc)) {
            $errors['ket_thuc'] = 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.';
        }

        $old = [
            'combo_id' => $comboId,
            'ten_chien_dich' => $tenChienDich,
            'ghi_chu' => $ghiChu,
            'bat_dau' => $batDau,
            'ket_thuc' => $ketThuc,
        ];

        return [
            'errors' => $errors,
            'old' => $old,
            'data' => [
                'combo_id' => $comboId,
                'ten_chien_dich' => $tenChienDich,
                'ghi_chu' => $ghiChu === '' ? null : $ghiChu,
                'bat_dau' => $batDau === '' ? null : $batDau,
                'ket_thuc' => $ketThuc === '' ? null : $ketThuc,
            ],
        ];
    }

    private function logException(string $action, Throwable $e, array $context = []): void
    {
        $path = dirname(__DIR__, 2) . '/storage/logs/combo_errors.log';
        $entry = [
            'time' => date('Y-m-d H:i:s'),
            'action' => $action,
            'message' => $e->getMessage(),
            'type' => get_class($e),
            'context' => $context,
        ];
        file_put_contents(
            $path,
            json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL,
            FILE_APPEND
        );
    }
}
