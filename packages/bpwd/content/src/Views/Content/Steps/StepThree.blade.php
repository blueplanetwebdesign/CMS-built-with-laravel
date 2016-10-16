@foreach ($layouts as $fieldset)
    <fieldset>
        <legend>{{ $fieldset['label'] }}</legend>
        @foreach ($fieldset['fields'] as $field)
            <?= FormHelper::getField($field) ?>
        @endforeach
    </fieldset>
@endforeach