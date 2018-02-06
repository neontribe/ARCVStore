<div class="add-child-form">
	<div class="form-group">
		<label for="dob-month" >Month</label>
		<input id="dob-month" name="dob-month" pattern="[0-9]*" min="0" max="12" type="number">
	</div>
	<div class="form-group">
		<label for="dob-year">Year</label>
		<input id="dob-year" name="dob-year" type="number" pattern="[0-9]*" min="0" max="{{ Carbon\Carbon::now()->year }}">
	</div>
	<button id="add-dob" class="add-dob">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </button>
</div>

<script>
$(document).ready(
    function() {
        var el = $("#child_wrapper");
        var monthEl = $('#dob-month');
        var yearEl = $('#dob-year');
        var addDateButton = $("#add-dob");

        $(addDateButton).click(function (e) {
            e.preventDefault();
            // If input fields are empty return, do not proceed.
            if (monthEl.val().length <= 1 || yearEl.val().length <= 1) {
                return false;
            }
            var dateString = yearEl.val() + '-' + monthEl.val();
            var humanDateString = new Date(dateString).toLocaleDateString('en-gb',{month:"short"}) +" "+ yearEl.val();
            $(el).append('<tr><td><input name="children[]" type="hidden" value="' +dateString+ '" >' + humanDateString + '</td><td><button type="button" class="remove_date_field"><i class="fa fa-minus" aria-hidden="true"></i></button></td></tr>');
            yearEl.val('');
            monthEl.val('');
            monthEl.focus();
        });

        $(el).on("click", ".remove_date_field", function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            return false;
        });
    }
);
</script>