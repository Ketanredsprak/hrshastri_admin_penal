<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <style>
        .modal-dialog-xl {
            max-width: 90%;
        }

        #calendar {
            margin-bottom: 100px;
        }

        #calendar .fc-view {
            margin-top: 0;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background-color: rgba(255, 220, 40, 0.15);
            background-color: rgba(81, 69, 157, 0.1);
        }

        .selected-event {
            position: relative;
            background-color: #4caf50;
            /* Green fill for selected dates */
            /* color: #fff; */
            height: 100%;
            width: 100%
        }

       
    </style>
</head>


<body>

    <div class="modal-body">
        {{-- <div class="modal-body model-xl" style="width: 100%" width="100%"> --}}
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="form-check form-switch">
                        <label for="is_custom_weekoffs">
                            <h5 class="">{{ __('Enable Custom Weekoff') }}
                            </h5>
                        </label>
                        <input type="checkbox" class="form-check-input" id="is_custom_weekoffs" name="is_custom_weekoffs"
                            {{ $is_custom_weekoffs == 1 ? 'checked' : '' }} value="1">
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="employees_ids_div" style="display: none;">

            <div class="form-group col-sm-12">
                {{ Form::label('employees_ids', __('Select Employees'), ['class' => 'form-label']) }}
                {{ Form::select('employees_ids[]', $employeesList, $selectedEmployeesIds, ['class' => 'form-control select2', 'id' => 'employees_ids', 'multiple' => true]) }}
            </div>

        </div>

        {{-- calender --}}
        <div class="row">
            <div class="col-md-12 m-auto">

                <input type="hidden" name="weekoffs_data" id="weekoffs_data" class="weekoffs_data"
                    value="{{ $weekoffs_data ?? '' }}">

                <input type="hidden" name="calender_type" id="calender_type" class="calender_type"
                    value="{{ $calenderType ?? 0 }}">

                <input type="hidden" name="employee_id" id="employee_id" class="employee_id"
                    value="{{ @$employeeDetails->id ?? 0 }}">

                <div id='calendar'></div>

            </div>
        </div>
        {{-- calender --}}

        <div class="float-end">
            <button type="button" id="btnSave" onclick="saveCalenderData()" class="btn btn-primary">Update</button>
        </div>

    </div>

</body>

</html>






<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js"></script>


<script>
    var isCalenderInitialize = false;

    var selectedDates = {};

    $(document).ready(function() {

        setTimeout(function() {
            @if ($is_custom_weekoffs == 1)

                @if ($calenderType == 0) // 0 = comnpany calender, 1 = empoloyee calender
                    $('#employees_ids').attr('required', 'required');
                    $("#employees_ids_div").show();
                @endif

                showCalender();
            @else

                @if ($calenderType == 0) // 0 = comnpany calender, 1 = empoloyee calender
                    $("#employees_ids_div").hide();
                    $("#calendar").hide();
                @endif
            @endif
        }, 200);
    });


    $('#is_custom_weekoffs').change(function() {
        if ($(this).prop('checked')) {

            @if ($calenderType == 0) // 0 = comnpany calender, 1 = empoloyee calender
                $('#employees_ids').attr('required', 'required');
                $("#employees_ids_div").show();
            @endif

            $("#calendar").show();
            showCalender();
        } else {

            @if ($calenderType == 0) // 0 = comnpany calender, 1 = empoloyee calender
                $('#employees_ids').removeAttr('required');
                $("#employees_ids_div").hide();
            @endif

            $("#calendar").hide()
        }
    });


    function showCalender() {

        if (isCalenderInitialize) {
            console.log("calander already initialize");
            return;
        }

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            buttonText: {
                today: 'Today'
            },
            dateClick: function(info) {
                var dateStr = info.dateStr;

                // Check if the date is already selected
                if (selectedDates[dateStr]) {
                    // Date is already selected, remove it
                    //     selectedDates[dateStr].remove();
                    //     delete selectedDates[dateStr];
                } else {
                    // Select new date and add event
                    var event = calendar.addEvent({
                        title: 'Week Off',
                        start: dateStr,
                        allDay: true,
                        className: 'selected-event',
                        color: '#1C232F'
                    });

                    selectedDates[dateStr] = event;
                }

                // saveToLocalStorage(Object.keys(selectedDates));

                $("#weekoffs_data").val(JSON.stringify(Object.keys(selectedDates)));
                // sendSelectedDates();
            },
            eventContent: function(info) {
                var deleteButton = document.createElement('button');
                deleteButton.className = 'delete-button';
                deleteButton.innerHTML = '<span class="material-icons">delete</span>';
                deleteButton.onclick = function() {
                    info.event.remove();
                    delete selectedDates[info.event.startStr];
                    //     saveToLocalStorage(Object.keys(selectedDates));
                    //     sendSelectedDates();
                    $("#weekoffs_data").val(JSON.stringify(Object.keys(selectedDates)));
                };

                var arrayOfDomNodes = [deleteButton]
                return {
                    domNodes: arrayOfDomNodes
                }
            }
        });
        calendar.render();


        // add data

        console.log("weekoffs_data")
        console.log('{!! $weekoffs_data ?? '' !!}');

        @if (!empty($weekoffs_data) && $weekoffs_data != '[]' && $weekoffs_data != null)

            var storedDates = JSON.parse('{!! $weekoffs_data ?? '' !!}');

            //  console.log('selectedDates ============>>>>>>>>>>>>> ', selectedDates);
            //  console.log('Week Off Json Data', storedDates);

            for (var dateStr of storedDates) {
                console.log("Event Date = ", dateStr);
                var event = calendar.addEvent({
                    title: 'Week Off',
                    start: dateStr,
                    allDay: true,
                    className: 'selected-event',
                    color: '#1C232F'
                });

                selectedDates[dateStr] = event;
            }
        @endif
        // add data

        isCalenderInitialize = true;
        console.log("Calendar initialize.");
    }


    function saveCalenderData() {

        var is_custom_weekoffs = $("#is_custom_weekoffs").prop('checked') ? 1 : 0;

        var employees_ids = $("#employees_ids").val();
        var weekoffs_data = $("#weekoffs_data").val();
        var calender_type = $("#calender_type").val();
        var employee_id = $("#employee_id").val();

        $.ajax({
            url: "{{ route('company-weekoff.store') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "is_custom_weekoffs": is_custom_weekoffs,
                "employees_ids": employees_ids,
                "weekoffs_data": weekoffs_data,
                "calender_type": calender_type,
                "employee_id": employee_id
            },
            success: function(data) {

                console.log("data", data);

                if (data.status == 1) {
                    $('#commonModal').modal('hide');
                    show_toastr('Success', data.message, 'success');
                } else {
                    show_toastr('Error', data.message, 'error');
                }

            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
                //  alert("Error: " + xhr.responseText);
                show_toastr('Error', 'Something went wrong', 'error');
            },
            complete: function(xhr, status) {
                console.log('Request complete');
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            show_toastr('Error', 'Something went wrong', 'error');
        });
    }
</script>
