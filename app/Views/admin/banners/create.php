<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Add New Banner</h1>
                <a href="<?= base_url('admin/banners') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Banners
                </a>
            </div>

            <?php if (session()->get('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session()->get('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('admin/banners') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="<?= old('title') ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="subtitle" class="form-label">Subtitle</label>
                                    <textarea class="form-control" id="subtitle" name="subtitle" rows="2"><?= old('subtitle') ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?= old('description') ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="button_text" class="form-label">Button 1 Text</label>
                                            <input type="text" class="form-control" id="button_text" name="button_text"
                                                   value="<?= old('button_text') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="button_link" class="form-label">Button 1 Link</label>
                                            <input type="text" class="form-control" id="button_link" name="button_link"
                                                   value="<?= old('button_link') ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="button_text_2" class="form-label">Button 2 Text</label>
                                            <input type="text" class="form-control" id="button_text_2" name="button_text_2"
                                                   value="<?= old('button_text_2') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="button_link_2" class="form-label">Button 2 Link</label>
                                            <input type="text" class="form-control" id="button_link_2" name="button_link_2"
                                                   value="<?= old('button_link_2') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Banner Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Max size: 2MB. Recommended: 500x400px</small>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="background_color" class="form-label">Background Color</label>
                                            <input type="color" class="form-control form-control-color" id="background_color"
                                                   name="background_color" value="<?= old('background_color', '#ff6b35') ?>">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="text_color" class="form-label">Text Color</label>
                                            <input type="color" class="form-control form-control-color" id="text_color"
                                                   name="text_color" value="<?= old('text_color', '#ffffff') ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order"
                                           value="<?= old('sort_order', 0) ?>" min="0">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                               value="1" <?= old('is_active') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= base_url('admin/banners') ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Banner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
