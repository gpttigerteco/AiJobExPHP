<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">شرح وظایف <?= htmlspecialchars($user['full_name'] ?? '') ?></h5>
            <small class="text-muted">نسخه جاری: <?= htmlspecialchars($jobDescription['version'] ?? 'N/A') ?></small>
        </div>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#suggestModal">پیشنهاد تغییر</button>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs" id="jdTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#jd-view" type="button">نمایش</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#jd-history" type="button">سوابق</button>
            </li>
        </ul>
        <div class="tab-content border border-top-0 p-3">
            <div class="tab-pane fade show active" id="jd-view">
                <pre class="mb-0"><?= htmlspecialchars($jobDescription['body_md'] ?? 'هنوز شرح وظایفی ثبت نشده است.') ?></pre>
            </div>
            <div class="tab-pane fade" id="jd-history">
                <?php if (!empty($history)): ?>
                    <div class="list-group">
                        <?php foreach ($history as $item): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <strong>نسخه <?= (int)$item['version'] ?></strong>
                                    <span class="text-muted small"><?= htmlspecialchars($item['created_at']) ?></span>
                                </div>
                                <pre class="mb-0 small text-muted"><?= htmlspecialchars(mb_strimwidth($item['body_md'], 0, 200, '...')) ?></pre>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">سابقه‌ای موجود نیست.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="suggestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="/me/jd/suggest-change" method="post" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">پیشنهاد تغییر</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                    <div class="mb-3">
                        <label class="form-label">متن پیشنهادی</label>
                        <textarea name="body" class="form-control" rows="6" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">یادداشت برای ناظر</label>
                        <textarea name="note" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary">ارسال پیشنهاد</button>
                </div>
            </form>
        </div>
    </div>
</div>
