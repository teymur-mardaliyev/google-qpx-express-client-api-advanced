<?php
if (isset($data["tickets"]->trips->tripOption)) {
    $airposts = $data["ticketModel"]->AirportDB($data["tickets"]->trips->data->airport);
    $cities = TicketAPI::CityDB($data["tickets"]->trips->data->city);
    $carrier = TicketAPI::CarrierDB($data["tickets"]->trips->data->carrier);
    $aircraft = TicketAPI::AircraftDB($data["tickets"]->trips->data->aircraft);
}
?>
<div class="search-box clear-fix">
    <div class="right-content">
        <div class="sort-panel clear-fix">
            <h4>Sort:</h4>
            <ul class="sort-types">
                <li class="sort-btn active" data-sort="price:asc">Price</li>
                <li class="sort-btn" data-sort="transplant:asc">Transit</li>
                <li class="sort-btn" data-sort="time:asc">Time</li>
            </ul>
        </div>

        <div class="search-results">
            <h1 class="search-title<?php echo $data["request"]->multi == 1 ? ' multi-title' : ''; ?>">
                <div class="centering">
                    <?php
                    $locations = array();
                    echo $data["html_title"];
                    ?>
                </div>
            </h1>

            <ul class="result-list" id="sortable-list">
                <?php
                if (isset($data["tickets"]->trips->tripOption)) {
                    foreach ($data["tickets"]->trips->tripOption as $key => $val) {

                        $slice_html = '';

                        foreach ($val->slice as $key1 => $segment) {

                            $first_segment_data = $segment->segment[0]->leg[0];
                            $segment_count = count($segment->segment) - 1;
                            $leg_count = count($segment->segment[count($segment->segment) - 1]->leg) - 1;

                            $last_segment_data = $segment->segment[$segment_count]->leg[$leg_count];

                            $legs = -1;

                            if (count($val->slice) > 2) {
                                $title_num = $key1 + 1;
                                $title = 'Uçuş #' . $title_num;
                            } else {
                                $title = $key1 == 0 ? 'Departure' : 'Return';
                            }


                            if (date('Ymd', strtotime($first_segment_data->departureTime)) < date('Ymd', strtotime($last_segment_data->arrivalTime))) {

                                $alert_message = 'Alert! Flight will start in <span class="alert-date"> ' . Timer::convertLocDate($first_segment_data->departureTime, '%e %B (%A)') . ' </span> and will end in <span class="alert-date"> ' . Timer::convertLocDate($last_segment_data->arrivalTime, '%e %B (%A)') . ' </span> date.';

                            }


                            $flight_time = Timer::convertHumanReadable($segment->duration, 'H:i');

                            $transplant = $legs <= 0 ? 'direct' : $legs . ' transplant';

                            $slice_html .= '<div class="flights-time-origns">
                    <div class="orign orign-dest clear-fix">
                        <div class="airport-time clear-fix">
                            <div class="datetime">
                                <span class="time">
                                    ' . Timer::convertDate($first_segment_data->departureTime, 'H:i') . '
                                </span>
                            </div>
                            <div class="airport">
                                ' . TicketAPI::unserRet($airposts->{$first_segment_data->origin}->data->city) . '
                            </div>
                        </div>
                        <div class="airport-name">
                            ' . $first_segment_data->origin . ', ' . TicketAPI::unserRet($airposts->{$first_segment_data->origin}->data->country) . '
                        </div>
                    </div>

                    <div class="flights-times">
                        <div class="flight-time">
                            ' . $flight_time . '
                        </div>
                        <span class="flight-timeline"></span>
                        <div class="flight-stops">
                            ' . $transplant . '
                        </div>
                    </div>

                    <div class="destination orign-dest clear-fix">
                        <div class="airport-time clear-fix">
                            <div class="airport">
                                ' . TicketAPI::unserRet($airposts->{$last_segment_data->destination}->data->city) . '
                            </div>
                            <div class="datetime">
                                <span class="time">
                                    ' . Timer::convertDate($last_segment_data->arrivalTime, 'H:i') . '
                                </span>
                            </div>
                        </div>
                        <div class="airport-name">
                            ' . $last_segment_data->destination . ', ' . TicketAPI::unserRet($airposts->{$last_segment_data->destination}->data->country) . '
                        </div>
                    </div>
                </div>';

                            if ($segment_count > $key1) {
                                $slice_html .= '<div class="line-on-flights"></div>';
                            }

                        }

                        $currency = TicketAPI::currency($val->saleTotal);

                        ?>

                        <li class="flight-list mix-target"
                            data-price="<?php echo str_replace(array("AZN", "USD", "$"), "", $val->saleTotal); ?>"
                            data-time="<?php echo $segment->duration; ?>"
                            data-transplant="<?php echo $legs; ?>">
                            <div class="short-card clear-fix">
                                <div class="flights">
                                    <?php echo $slice_html; ?>
                                </div>
                                <div class="flight-price">
                                    <span class="price"><?php echo $currency; ?></span>
                                </div>
                            </div>
                        </li>
                        <?php

                    }
                } else {
                    echo "<div class='alert alert-danger'>Sorry, there could not be found any flight.</div>";
                }
                ?>
            </ul>
        </div>

    </div>
</div>