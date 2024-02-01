<?php

Route::middleware(['web'])->prefix('image')->as('image.')->group(function () {
    Route::get('resize/{image}/{width}/{height}', [\ReesMcIvor\ImageTools\Controllers\ImageController::class, 'resize'])->name('resize');
});