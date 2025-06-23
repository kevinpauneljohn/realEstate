<div id='appointment-calendar'></div>

@section('plugins.Fullcalendar',true)
@section('plugins.CustomCSS',true)

@once
    @push('js')
        <script>
            let clientInfoModal = $('#client-info');
            $(document).ready(function(){

                bookingCalendar();

                $('.fc-bookAppointment-button').attr('data-bs-toggle','tooltip').attr('data-bs-placement','top').attr('title','Book Appointment')
                    .html('<span class="fa fa-calendar"></span>');

                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            const bookingCalendar = () => {
                let calendarEl = document.getElementById('appointment-calendar');
                let clientCalendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'listWeek',
                    headerToolbar: {
                        left  : 'prev,next today bookAppointment',
                        center: 'title',
                        right : 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                    },
                    displayEventTime: true,
                    slotEventOverlap: true,
                    dayMaxEventRows: true,
                    customButtons: {
                        bookAppointment: {
                            icon: "- glyphicon glyphicon-calendar",
                            // text: "Create",
                            click: function() {
                                $('#book-client').modal('toggle');
                            }
                        }
                    },
                    themeSystem: 'bootstrap',
                    editable: true,
                    selectable: true,
                    selectConstraint:{
                        start: '00:00',
                        end: '24:00'
                    },
                    select: function(info){
                        let selectedDate = info.startStr;

                        if(moment(selectedDate).isBefore(moment('{{now()}}')))
                        {
                        }
                    },
                    events: '{!! route('get.all.tasks') !!}',
                    dateClick: function (info){
                        let selectedDate = info.dateStr;

                        if(moment(selectedDate).isBefore(moment('{{now()}}')))
                        {
                            Swal.fire('Warning!', 'The date selected cannot be processed', 'warning')
                        }else{
                            $('#book-client').find('input[name=appointment_date]').val(moment(info.date).format('MM/DD/YYYY hh:mm A'));
                            $('#book-client').modal('toggle');
                        }
                    },
                    eventClick: function(info){
                        window.location.href = '/tasks/overview/'+info.event.id;
                    }

                });
                clientCalendar.render();
            }

        </script>
    @endpush
@endonce
