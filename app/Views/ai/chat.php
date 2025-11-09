<div class="row g-3">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">چت سازمانی</h5>
                <span class="badge bg-light text-dark">RAG فعال</span>
            </div>
            <div class="card-body d-flex flex-column" style="height: 480px;">
                <div class="flex-grow-1 overflow-auto mb-3" id="chat-messages">
                    <div class="text-center text-muted my-5">برای شروع گفتگو پیام خود را ارسال کنید.</div>
                </div>
                <form id="chat-form" class="d-flex gap-2">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                    <textarea class="form-control" name="message" rows="2" placeholder="سؤال خود را تایپ کنید..." required></textarea>
                    <button class="btn btn-primary align-self-end" type="submit">ارسال</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header"><h6 class="mb-0">منابع استناد</h6></div>
            <div class="card-body" id="chat-citations">
                <p class="text-muted">پس از پاسخ، منابع مرتبط اینجا نمایش داده می‌شود.</p>
            </div>
        </div>
    </div>
</div>
<script>
    (function () {
        const form = document.getElementById('chat-form');
        const messages = document.getElementById('chat-messages');
        const citations = document.getElementById('chat-citations');
        form?.addEventListener('submit', async function (event) {
            event.preventDefault();
            const formData = new FormData(form);
            const message = formData.get('message');
            if (!message) { return; }
            messages.insertAdjacentHTML('beforeend', `<div class="chat-message bg-primary text-white mb-2">${message}</div>`);
            form.reset();
            const response = await fetch('/api/ai/message', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': formData.get('_token')},
                body: formData,
            });
            const data = await response.json();
            if (data.answer) {
                messages.insertAdjacentHTML('beforeend', `<div class="chat-message bg-light mb-2 text-dark">${data.answer}</div>`);
            }
            if (Array.isArray(data.citations)) {
                citations.innerHTML = data.citations.map(c => `<div class="mb-2"><strong>${c.title}</strong><div class="small text-muted">${c.type}</div></div>`).join('');
            }
        });
    })();
</script>
