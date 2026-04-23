<section class="card">
    <div style="margin-bottom: 20px;">
        <span class="badge">Đăng nhập hệ thống</span>
        <h2 style="margin: 12px 0 8px;">Quản lý dữ liệu tập trung</h2>
        <p class="muted" style="margin: 0;">Phân loại dữ liệu rõ ràng, tra cứu nhanh và kiểm soát truy cập theo vai trò.</p>
    </div>

    <form method="POST" action="<?= e(base_url('login')) ?>" class="form-grid">
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

        <div>
            <label for="email">Email đăng nhập</label>
            <input id="email" type="email" name="email" value="<?= e((string) session_old('email', 'admin@easynet.local')) ?>" required>
        </div>

        <div>
            <label for="password">Mật khẩu</label>
            <input id="password" type="password" name="password" value="password" required>
        </div>

        <button type="submit">Đăng nhập</button>
    </form>
</section>
