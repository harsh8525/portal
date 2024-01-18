@php
$countries = App\Models\Country::with('countryCode')
->where('status','active')
->orderBy('iso_code', 'asc')
->get();
@endphp

<select name="{{ $name }}" id="{{ $id }}" class="{{ $class }}" style="width:100%" @if(isset($multiple)){{ $multiple ? 'multiple' : '' }}@endif>
    <option value="">{{ $placeholder }}</option>
    @foreach($countries as $country)
    @if(isset($multiple))
    <option value="{{ $country->iso_code }}" data-cid="{{ $country->id }}" {{ in_array($country->iso_code, $selected) ? 'selected' : '' }}>
        @foreach($country->countryCode as $country_name)
        {{$country_name['country_name']}} <br>
        @endforeach
    </option>
    @else
    <option value="{{ $country->iso_code }}" data-cid="{{ $country->id }}" {{ $country->iso_code == $selected ? 'selected' : '' }}>
        @foreach($country->countryCode as $country_name)
        {{$country_name['country_name']}} <br>
        @endforeach
    </option>
    @endif
    @endforeach
</select>
<script src="{{ URL::asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#{{$id}}').select2({
            ajax: {
                url: '/get-country-name',
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
                    var mappedData = $.map(data, function(country) {
                        return {
                            id: country.iso_code,
                            text: country.cname
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
            }
        });
        var $eventSelect = $('#{{$id}}');

        if ($eventSelect.length > 0) {
            $eventSelect.select2(); // Initialize Select2

            $eventSelect.on('change', function(e) {
        // $('#{{$id}}').on('change', function() {
            var country_code = $(this).val();
            var cid = $eventSelect.find(':selected').data('cid');

            // alert(country_code);
            // alert(cid);
            $('#city_code').empty();
            $('#state_code').empty();

            // if ($(this).valid()) {
            //     $(this).removeClass('is-invalid');
            //     $(this).next('.invalid-feedback').remove();
            // }

            if (country_code) {
                $('#state_code').select2({
                    ajax: {
                        url: '/get-state-name/' + country_code,
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
                            var mappedData = $.map(data, function(state) {
                                return {
                                    id: state.id,
                                    text: state.sname
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
                    placeholder: 'Select state',
                });
                $('#city_code').select2({
                    ajax: {
                        url: '/get-city-name/' + country_code,
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
                    placeholder: 'Select city',
                });
            };
        });
        $('#{{$id}}').trigger('change.select2');
        // $('#{{$id}}').trigger('change');
    }
    })
</script>