<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-5">
            <h1 class="fw-bold mb-4">Get in Touch</h1>
            <p class="text-muted mb-4">Have a question or need help? We're here for you.</p>
            <div class="d-flex align-items-center mb-3">
                <div style="width:44px;height:44px;border-radius:12px;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-geo-alt"></i></div>
                <div class="ms-3"><strong>Address</strong><br><span class="text-muted small"><?= App::getSetting('shop_address', '') ?></span></div>
            </div>
            <div class="d-flex align-items-center mb-3">
                <div style="width:44px;height:44px;border-radius:12px;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-envelope"></i></div>
                <div class="ms-3"><strong>Email</strong><br><span class="text-muted small"><?= App::getSetting('shop_email', '') ?></span></div>
            </div>
            <div class="d-flex align-items-center mb-4">
                <div style="width:44px;height:44px;border-radius:12px;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-telephone"></i></div>
                <div class="ms-3"><strong>Phone</strong><br><span class="text-muted small"><?= App::getSetting('shop_phone', '') ?></span></div>
            </div>
            <div class="border rounded-3 p-4 bg-light">
                <h6 class="fw-bold mb-2"><i class="bi bi-clock me-1"></i> Business Hours</h6>
                <p class="small text-muted mb-1">Mon – Fri: 8:00 AM – 6:00 PM</p>
                <p class="small text-muted mb-1">Saturday: 9:00 AM – 4:00 PM</p>
                <p class="small text-muted mb-0">Sunday: Closed</p>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-chat-dots me-2"></i>Send us a Message</h5>
                    <form method="POST" action="<?= base_url('contact') ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Your Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Your Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Subject</label>
                                <input type="text" name="subject" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Message</label>
                                <textarea name="message" rows="5" class="form-control" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-send me-2"></i>Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>