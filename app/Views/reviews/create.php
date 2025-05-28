<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-star text-warning"></i> Write a Review
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Product Info -->
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                        <?php if ($product['image']): ?>
                            <img src="<?= base_url('uploads/products/' . $product['image']) ?>" 
                                 alt="<?= esc($product['name']) ?>" 
                                 class="me-3" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        <?php else: ?>
                            <div class="me-3 bg-secondary d-flex align-items-center justify-content-center text-white" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-image fa-2x"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h5 class="mb-1"><?= esc($product['name']) ?></h5>
                            <p class="text-muted mb-0"><?= esc($product['short_description']) ?></p>
                        </div>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->get('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->get('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Review Form -->
                    <form method="post" action="<?= base_url('reviews') ?>">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label">Rating <span class="text-danger">*</span></label>
                            <div class="rating-input">
                                <div class="star-rating">
                                    <input type="radio" name="rating" value="5" id="star5" required>
                                    <label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" name="rating" value="4" id="star4">
                                    <label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" name="rating" value="3" id="star3">
                                    <label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" name="rating" value="2" id="star2">
                                    <label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" name="rating" value="1" id="star1">
                                    <label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                                </div>
                                <div class="rating-text mt-2">
                                    <span id="rating-text" class="text-muted">Click to rate</span>
                                </div>
                            </div>
                        </div>

                        <!-- Review Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Review Title (Optional)</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="title" 
                                   name="title" 
                                   placeholder="Summarize your review in a few words"
                                   value="<?= old('title') ?>"
                                   maxlength="255">
                        </div>

                        <!-- Review Text -->
                        <div class="mb-4">
                            <label for="review" class="form-label">Your Review (Optional)</label>
                            <textarea class="form-control" 
                                      id="review" 
                                      name="review" 
                                      rows="5" 
                                      placeholder="Share your experience with this product..."
                                      maxlength="1000"><?= old('review') ?></textarea>
                            <div class="form-text">Maximum 1000 characters</div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('product/' . $product['slug']) ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Product
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.star-rating input {
    display: none;
}

.star-rating label {
    color: #ddd;
    font-size: 1.5rem;
    cursor: pointer;
    transition: color 0.2s;
    margin-right: 5px;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #ffc107;
}

.rating-input {
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    background-color: #f8f9fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const ratingText = document.getElementById('rating-text');
    
    const ratingTexts = {
        1: '1 Star - Poor',
        2: '2 Stars - Fair',
        3: '3 Stars - Good',
        4: '4 Stars - Very Good',
        5: '5 Stars - Excellent'
    };
    
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            ratingText.textContent = ratingTexts[this.value];
            ratingText.className = 'text-warning fw-bold';
        });
    });
});
</script>

<?= $this->endSection() ?>
