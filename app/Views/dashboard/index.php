<div class="row g-3">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">شرح وظایف من</h5>
                <a href="/me/jd" class="btn btn-sm btn-primary">مشاهده کامل</a>
            </div>
            <div class="card-body">
                <?php if (!empty($jobDescription)): ?>
                    <h6><?= htmlspecialchars($jobDescription['title']) ?></h6>
                    <p class="text-muted">نسخه <?= (int)$jobDescription['version'] ?></p>
                    <div class="bg-light p-3 rounded" style="max-height: 240px; overflow-y: auto;">
                        <pre class="mb-0 text-wrap"><?= htmlspecialchars(mb_strimwidth($jobDescription['body_md'], 0, 400, '...')) ?></pre>
                    </div>
                <?php else: ?>
                    <p class="text-muted">شرح وظایفی ثبت نشده است.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4 d-flex flex-column gap-3">
        <div class="card shadow-sm">
            <div class="card-header"><h6 class="mb-0">مستندات جدید واحد</h6></div>
            <ul class="list-group list-group-flush">
                <?php if (!empty($documents)): ?>
                    <?php foreach ($documents as $doc): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?= htmlspecialchars($doc['title']) ?></span>
                            <span class="badge bg-secondary">نسخه <?= (int)$doc['version'] ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted">مستندی ثبت نشده است.</li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="card shadow-sm">
            <div class="card-header"><h6 class="mb-0">سؤالات پرتکرار</h6></div>
            <div class="list-group list-group-flush">
                <?php if (!empty($faqs)): ?>
                    <?php foreach ($faqs as $faq): ?>
                        <div class="list-group-item">
                            <strong><?= htmlspecialchars($faq['question']) ?></strong>
                            <p class="mb-0 small text-muted"><?= htmlspecialchars(mb_strimwidth($faq['answer'], 0, 120, '...')) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="list-group-item text-muted">سؤالی ثبت نشده است.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
