<div class="add-child-form">
	<div class="form-group">
		<label for="dob-month">Month</label>
		<input id="dob-month" name="dob-month" type="number" pattern="[0-9]*" min="0" max="12">
	</div>
	<div class="form-group">
		<label for="dob-year">Year</label>
		<input id="dob-year" name="dob-year" type="number" pattern="[0-9]*" min="0" max="{{ Carbon\Carbon::now()->year }}">
	</div>
	<button id="add-dob" class="add-dob">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </button>
</div>