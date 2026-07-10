<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-4"><?= $page['title'] ?? 'Page' ?></h1>
            <div class="page-content">
                <?= $page['content'] ?? '<p class="text-muted">No content available.</p>' ?>
            </div>
        </div>
    </div>
</div>
<style>
.page-content h3 { margin-top: 1.5rem; color: var(--primary); }
.page-content p { line-height: 1.8; color: #4b5563; }
</style>