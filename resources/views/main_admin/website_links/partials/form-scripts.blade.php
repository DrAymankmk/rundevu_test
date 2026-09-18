<script>
$(document).ready(function() {
	function syncIconPreview() {
		var icon = $('#icon-input').val() || 'fas fa-share-alt';
		$('#icon-preview').html('<i class="' + icon + '"></i>');
	}

	$('#preset-select').on('change', function() {
		var option = $(this).find('option:selected');
		var key = option.val();
		if (!key) {
			return;
		}

		$('#key-input').val(key);
		$('#type-select').val(option.data('type'));
		$('#icon-input').val(option.data('icon'));
		$('#color-input').val(option.data('color'));
		syncIconPreview();
	});

	$('#icon-input').on('input', syncIconPreview);
	syncIconPreview();
});
</script>
