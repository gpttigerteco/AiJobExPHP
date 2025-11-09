<div class="row g-3">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">فهرست مستندات</h5></div>
            <div class="card-body">
                <p class="text-muted">در این بخش می‌توانید مستندات آپلود شده را مدیریت کنید.</p>
                <div class="alert alert-info">برای مشاهده واقعی نیاز به اتصال به دیتابیس است.</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header"><h6 class="mb-0">بارگذاری مستند جدید</h6></div>
            <div class="card-body">
                <form action="/admin/documents/upload" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                    <div class="mb-3">
                        <label class="form-label">عنوان</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">فایل</label>
                        <input type="file" class="form-control" name="file" required>
                        <div class="form-text">فرمت‌های مجاز: PDF, Markdown, DOCX</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">آپلود</button>
                </form>
            </div>
        </div>
    </div>
</div>
