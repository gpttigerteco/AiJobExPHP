<div class="row g-3">
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">تنظیمات عمومی</h5></div>
            <div class="card-body">
                <form class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">نام سامانه</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars(env('APP_NAME', 'AiJobExPHP')) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">نشانی سایت</label>
                        <input type="url" class="form-control" value="<?= htmlspecialchars(env('APP_URL')) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">ذخیره</button>
                </form>
            </div>
        </div>
    </div>
</div>
