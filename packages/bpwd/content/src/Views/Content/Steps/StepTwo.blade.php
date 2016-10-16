<!-- Category select list -->
<?= Form::adminSelect('layout_id', $data->layout_list, "Layout:", $data->selected_layout, ["class" => "form-control", $data->disabled]); ?>