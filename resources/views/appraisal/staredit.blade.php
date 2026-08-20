<style>
     .rate {
          float: left;
          height: 46px;
          padding: 0 10px;
     }

     .rate:not(:checked)>input {
          position: absolute;
          top: -9999px;
     }

     .rate:not(:checked)>label {
          float: right;
          width: 1em;
          overflow: hidden;
          white-space: nowrap;
          cursor: pointer;
          font-size: 30px;
          color: #ccc;
     }

     .rate:not(:checked)>label:before {
          content: '★ ';
     }

     .rate>input:checked~label {
          color: #ffc700;
     }

     .rate:not(:checked)>label:hover,
     .rate:not(:checked)>label:hover~label {
          color: #deb217;
     }

     .rate>input:checked+label:hover,
     .rate>input:checked+label:hover~label,
     .rate>input:checked~label:hover,
     .rate>input:checked~label:hover~label,
     .rate>label:hover~input:checked~label {
          color: #c59b08;
     }

     /* Modified from: https://github.com/mukulkant/Star-rating-using-pure-css */
</style>


{{-- <div class='row'> --}}
<div class="col-5  text-end" style="margin-left: 51px;">
     <h5>{{__('Indicator')}}</h5>
</div>
<div class="col-4  text-end">
     <h5>{{__('Appraisal')}}</h5>
</div>
@foreach ($performance_types as $performance_type)
<div class="col-md-12 mt-3">
     <h6>{{ $performance_type->name }}</h6>
     <hr class="mt-0">
</div>

@foreach ($performance_type->types as $types)
<div class="col-4">
     {{ $types->name }}
</div>
<div class="col-4">
     <!-- <fieldset id='demo' class="rate">
          <input class="stars" type="radio" id="technical-5*-{{ $types->id }}" name="ratings[{{ $types->id }}]" value="5" {{ isset($ratings[$types->id]) && $ratings[$types->id] == 5 ? 'checked' : '' }} disabled>
          <label class="full" for="technical-5*-{{ $types->id }}" title="Awesome - 5 stars"></label>
          <input class="stars" type="radio" id="technical-4*-{{ $types->id }}" name="ratings[{{ $types->id }}]" value="4" {{ isset($ratings[$types->id]) && $ratings[$types->id] == 4 ? 'checked' : '' }} disabled>
          <label class="full" for="technical-4*-{{ $types->id }}" title="Pretty good - 4 stars"></label>
          <input class="stars" type="radio" id="technical-3*-{{ $types->id }}" name="ratings[{{ $types->id }}]" value="3" {{ isset($ratings[$types->id]) && $ratings[$types->id] == 3 ? 'checked' : '' }} disabled>
          <label class="full" for="technical-3*-{{ $types->id }}" title="Meh - 3 stars"></label>
          <input class="stars" type="radio" id="technical-2*-{{ $types->id }}" name="ratings[{{ $types->id }}]" value="2" {{ isset($ratings[$types->id]) && $ratings[$types->id] == 2 ? 'checked' : '' }} disabled>
          <label class="full" for="technical-2*-{{ $types->id }}" title="Kinda bad - 2 stars"></label>
          <input class="stars" type="radio" id="technical-1*-{{ $types->id }}" name="ratings[{{ $types->id }}]" value="1" {{ isset($ratings[$types->id]) && $ratings[$types->id] == 1 ? 'checked' : '' }} disabled>
          <label class="full" for="technical-1*-{{ $types->id }}" title="Sucks big time - 1 star"></label>
     </fieldset> -->

     @php
     if (!empty($indicator->rating)) {
     $rating = json_decode($indicator->rating, true);
     if (!empty($rating)) {
     $starsum = array_sum($rating);
     $overallrating = $starsum / count($rating);
     } else {
     $overallrating = 0;
     }
     } else {
     $overallrating = 0;
     }
     @endphp

     <div>
          @for ($i = 1; $i <= 5; $i++) @if ($overallrating < $i) @if (is_float($overallrating) && round($overallrating)==$i) <i class="text-warning fas fa-star-half-alt"></i>
               @else
               <i class="fas fa-star"></i>
               @endif
               @else
               <i class="text-warning fas fa-star"></i>
               @endif
               @endfor
               <span class="theme-text-color">({{ number_format($overallrating, 1) }})</span>
     </div>
</div>
<div class="col-4">
     <fieldset id='demo1' class="rate">
          <input class="stars" type="radio" id="technical-5-{{ $types->id }}" name="rating[{{ $types->id }}]" value="5" {{ isset($rating[$types->id]) && $rating[$types->id] == 5 ? 'checked' : '' }}>
          <label class="full" for="technical-5-{{ $types->id }}" title="Awesome - 5 stars"></label>
          <input class="stars" type="radio" id="technical-4-{{ $types->id }}" name="rating[{{ $types->id }}]" value="4" {{ isset($rating[$types->id]) && $rating[$types->id] == 4 ? 'checked' : '' }}>
          <label class="full" for="technical-4-{{ $types->id }}" title="Pretty good - 4 stars"></label>
          <input class="stars" type="radio" id="technical-3-{{ $types->id }}" name="rating[{{ $types->id }}]" value="3" {{ isset($rating[$types->id]) && $rating[$types->id] == 3 ? 'checked' : '' }}>
          <label class="full" for="technical-3-{{ $types->id }}" title="Meh - 3 stars"></label>
          <input class="stars" type="radio" id="technical-2-{{ $types->id }}" name="rating[{{ $types->id }}]" value="2" {{ isset($rating[$types->id]) && $rating[$types->id] == 2 ? 'checked' : '' }}>
          <label class="full" for="technical-2-{{ $types->id }}" title="Kinda bad - 2 stars"></label>
          <input class="stars" type="radio" id="technical-1-{{ $types->id }}" name="rating[{{ $types->id }}]" value="1" {{ isset($rating[$types->id]) && $rating[$types->id] == 1 ? 'checked' : '' }}>
          <label class="full" for="technical-1-{{ $types->id }}" title="Sucks big time - 1 star"></label>
     </fieldset>
</div>

@endforeach
@endforeach