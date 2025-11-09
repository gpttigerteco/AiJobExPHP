<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">گزارش‌های مدیریتی</h5>
        <a class="btn btn-sm btn-outline-secondary" href="/reports/export">خروجی CSV</a>
    </div>
    <div class="card-body">
        <form class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <label class="form-label">از تاریخ</label>
                <input type="date" class="form-control" name="from" value="<?= htmlspecialchars($_GET['from'] ?? date('Y-m-01')) ?>">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">تا تاریخ</label>
                <input type="date" class="form-control" name="to" value="<?= htmlspecialchars($_GET['to'] ?? date('Y-m-d')) ?>">
            </div>
            <div class="col-12 col-md-4 align-self-end">
                <button class="btn btn-primary w-100">به‌روزرسانی</button>
            </div>
        </form>
        <div class="row g-3">
            <div class="col-12 col-lg-3">
                <div class="card text-bg-primary shadow-sm">
                    <div class="card-body">
                        <p class="mb-1">درخواست‌های در انتظار</p>
                        <h3 class="fw-bold">12</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card text-bg-success shadow-sm">
                    <div class="card-body">
                        <p class="mb-1">هزینه ماهانه AI</p>
                        <h3 class="fw-bold">$245</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card text-bg-warning shadow-sm">
                    <div class="card-body">
                        <p class="mb-1">سؤالات بی‌پاسخ</p>
                        <h3 class="fw-bold">5</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card text-bg-dark shadow-sm">
                    <div class="card-body">
                        <p class="mb-1">کاربران فعال</p>
                        <h3 class="fw-bold">87</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
