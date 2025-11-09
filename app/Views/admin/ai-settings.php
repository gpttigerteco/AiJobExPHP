<div class="card shadow-sm">
    <div class="card-header"><h5 class="mb-0">تنظیمات هوش مصنوعی</h5></div>
    <div class="card-body">
        <form class="row g-3 needs-validation" novalidate>
            <div class="col-12">
                <label class="form-label">ارائه‌دهنده</label>
                <select class="form-select" required>
                    <option value="mock" <?= $provider === 'mock' ? 'selected' : '' ?>>Mock</option>
                    <option value="openai" <?= $provider === 'openai' ? 'selected' : '' ?>>OpenAI</option>
                    <option value="azure">Azure OpenAI</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">کلید API</label>
                <input type="password" class="form-control" placeholder="••••••">
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary">ذخیره تنظیمات</button>
            </div>
        </form>
    </div>
</div>
