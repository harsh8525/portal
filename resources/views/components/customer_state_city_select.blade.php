<?php
$states = App\Models\State::query();
$states->with('stateName');
$states->where('status','active');
if(isset($country_code)){
$states->where('country_code',$country_code);
}
$states->orderBy('iso_code', 'asc');

$states = $states->get();
?>
<select name="{{ $name }}" id="{{ $id }}" class="{{ $class }}" style="width:100%" @if(isset($multiple)){{ $multiple ? 'multiple' : '' }}@endif>
<option value="">{{ $placeholder }}</option>
    @foreach($states as $state)
    @if(isset($multiple))
    <option value="{{ $state->id }}" data-cid="{{ $state->iso_code }}" {{ in_array($state->id, $selected) ? 'selected' : '' }}>
        @foreach($state->stateName as $state_name)
        {{$state_name['state_name']}} <br>
        @endforeach
    </option>
    @else
    <option value="{{ $state->id }}" data-cid="{{ $state->iso_code }}" {{ $state->id == $selected ? 'selected' : '' }}>
        @foreach($state->stateName as $state_name)
        {{$state_name['state_name']}} <br>
        @endforeach
    </option>
    @endif
    @endforeach
</select>
<script src="{{ URL::asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
   
    $(document).ready(function() {
        var $eventSelect = $('#{{$id}}');

        if ($eventSelect.length > 0) {
            $eventSelect.select2(); // Initialize Select2

            $eventSelect.on('change', function(e) {
                var state_id = $(this).val();
                var state_code = $eventSelect.find(':selected').data('cid');
                // alert(state_code);
                $('#city_code').empty();

                if (state_code) {
                    $('#city_code').select2({
                        ajax: {
                            url: '/get-city-name/' + state_code,
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    term: params.term,
                                    page: params.page || 1,
                                    "_token": '{{ csrf_token() }}'
                                };
                            },
                            processResults: function(data) {
                                console.log(data);
                                var mappedData = $.map(data, function(city) {
                                    return {
                                        id: city.id,
                                        text: city.cname
                                    };
                                });

                                return {
                                    results: mappedData,
                                    pagination: {
                                        more: mappedData.length >= 10
                                    }
                                };
                            },
                            cache: true
                        },
                        placeholder: 'Select City',
                    });
                };
                // });
            });
            // $('#state_code').trigger('change');
        }
    });
</script>