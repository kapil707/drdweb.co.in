<script>
function find_medicine_category()
{	
	$.ajax({
        type: "POST",
        data: { id: '1' },
        url: "<?= base_url()?>admin/manage_medicine/find_medicine_category",
        cache: false,
        dataType: 'json',
        success: function(response) {
            if (response.success === "1") {
                // Dropdown ko clear karen
                $('#find_medicine_category').empty();

                // Default option add karen
                $('#find_medicine_category').append(`<option value="">Select Medicine category</option>`);

                // Items loop kar ke options add karen
                response.items.forEach(function(item) {
                    $('#find_medicine_category').append(
                        `<option value="${item.item_code}">${item.item_name} (${item.item_code})</option>`
                    );
                });
            } else {
                console.error('Error:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
}
</script>