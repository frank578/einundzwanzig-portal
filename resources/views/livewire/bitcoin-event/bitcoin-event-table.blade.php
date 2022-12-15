<div class="bg-21gray flex flex-col h-screen justify-between">
    {{-- HEADER --}}
    <livewire:frontend.header :country="$country"/>
    {{-- MAIN --}}
    <section class="w-full mb-12">
        <div class="max-w-screen-2xl mx-auto px-2 sm:px-10 space-y-4" id="table">
            <div class="">

                <link rel="stylesheet" type="text/css"
                      href="https://unpkg.com/js-year-calendar@latest/dist/js-year-calendar.min.css"/>
                <script src="https://unpkg.com/js-year-calendar@latest/dist/js-year-calendar.min.js"></script>
                <script src="https://unpkg.com/js-year-calendar@latest/locales/js-year-calendar.de.js"></script>

                <style>
                    .calendar .calendar-header {
                        background-color: #F7931A;
                        color: white;
                        border: 0;
                    }

                    .calendar table.month th.month-title {
                        color: #fff;
                    }

                    .calendar table.month th.day-header {
                        color: #fff;
                    }

                    .calendar table.month td.day .day-content {
                        color: #fff;
                    }
                </style>

                <div
                    wire:ignore
                    x-data="{
                        calendar: null,
                        init() {
                            let events = {{ Js::from($events) }};
                            events = events.map(function(e){
                                return {id: e.id, startDate: new Date(e.startDate), endDate: new Date(e.endDate), location: e.location, description: e.description}
                            })

                            new Calendar(this.$refs.calendar, {
                                style: 'background',
                                language: 'de',
                                startYear: {{ $year }},
                                dataSource: events,
                                yearChanged: function(e) {
                                    @this.set('year', e.currentYear);
                                },
                                clickDay: function(e) {
                                    if(e.events.length > 0) {
                                        var content = '';
                                        var ids = [];

                                        for(var i in e.events) {
                                            ids.push(e.events[i].id);
                                            content += '<div class=\'event-tooltip-content\'>'
                                            + '<div class=\'event-name\'>' + e.events[i].location + '</div>'
                                            + '<div class=\'event-location\'>' + e.events[i].description + '</div>'
                                            + '</div>';
                                        }
                                        console.log(content);

                                        $wire.call('popover', content, ids.join(','));
                                    }
                                },
                            });
                        },
                    }"
                >
                    <div x-ref="calendar"></div>
                </div>
            </div>

            <livewire:tables.bitcoin-event-table :country="$country->code"/>
        </div>
    </section>
    {{-- FOOTER --}}
    <livewire:frontend.footer/>
</div>
