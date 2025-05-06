$(document).ready(function () {
    function loadProducts(page = 1) {
        $.ajax({
            url: 'load_products.php',
            method: 'GET',
            data: { page },
            dataType: 'json',
            success: function (res) {
                $('#product-container').html(res.html);
                renderPagination(page, res.pages);
            }
        });
    }

    function renderPagination(current, total) {
        let html = '';

        if (current > 1) {
            html += `<button class="px-2" data-page="${current - 1}">&lsaquo;</button>`;
        }

        for (let i = 1; i <= total; i++) {
            html += `<button class="pagination-btn ${i === current ? 'active' : ''}" data-page="${i}">${i}</button>`;
        }

        if (current < total) {
            html += `<button class="px-2" data-page="${current + 1}">&rsaquo;</button>`;
        }

        $('#pagination').html(html);
    }

    $(document).on('click', '.pagination-btn, #pagination button', function () {
        const page = $(this).data('page');
        if (page) loadProducts(page);
    });

    loadProducts(); // Load pertama kali
});