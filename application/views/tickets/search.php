<div class="search-box clear-fix">
    <div class="right-content">
        <div class="search-waiter">
            <h1 class="search-title<?php echo $data["request"]->multi == 1 ? ' multi-title' : ''; ?>">
                <div class="centering">
                    <?php

                    $slice = json_decode($data["request"]->slice);

                    $locations = array();

                    foreach ($slice as $key => $val) {
                        $next = $key + 1;
                        if ($data["request"]->multi == 1 || $key == 0) {
                            $departure = $data["airportModel"]->getAirportByCode($val->origin);
                            echo '<span class="airport-city">' . TicketAPI::unserRet($departure->city) . '</span>';
                            TicketAPI::AirportOnMap($departure);
                        }

                        echo ' <span class="longrightarrow"></span> ';
                        $arrival = $data["airportModel"]->getAirportByCode($val->destination);
                        echo '<span class="airport-city">' . TicketAPI::unserRet($arrival->city) . '</span>';
                        TicketAPI::AirportOnMap($arrival);

                        if ($data["request"]->multi == 1 && isset($slice[$next])) {
                            echo ' <span class="rang"></span> ';
                        }
                    }
                    ?>
                </div>
            </h1>
            <div class="loading-info">
                Please wait while we are searching results.
            </div>
            <div class="error-messages">
                <?php

                if ($data["request"]->error_info || strtotime($slice[0]->date) < strtotime(date("Y-m-d"))) {
                    $data["run-search"] = false;
                    Session::add("feedback_negative", "The date you entered is not correct, please enter correct date.");
                    $this->renderFeedbackMessages();
                } else {
                    $data["run-search"] = true;
                }
                ?>
            </div>
            <div id="map-canvas"></div>
        </div>

    </div>
</div>

<script type="text/javascript">
    var runGoogleMap = true;
    var locations = <?php echo json_encode(TicketAPI::$locations);?>;
    var searchRequest = "<?php echo Router::get('request'); ?>",
        searchStart = <?php echo $data["run-search"] == false ? "false" : "true"; ?>
</script>