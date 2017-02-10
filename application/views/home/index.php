<div class="ticketParametrs">
    <div class="parametrs">
        <h2>Search</h2>
        <form name="ticketParametrs" method="post" action="/search/request/" class="search-tickets-form single active-form">
            <input type="hidden" name="data[multi]" value="0">
            <div class="ticketDestinations">
                <div class="ticketLocations clear-fix">
                    <div class="departureAirport">
                        <label>From</label>
                        <div class="inputAirport">
                            <input type="text" placeholder="City/Airport" id="departureAirport" class="search-airport" autocomplete="off" required="required" onclick="this.setSelectionRange(0, this.value.length)" value="Bakı - Bakı Beynəlxalq Heydər Aliyev Hava limanı">
                            <input type="hidden" name="data[departureAirport]" class="hidden-input-code" value="GYD">
                        </div>
                    </div>
                    <div class="change-icon backway"></div>
                    <div class="arrivalAirport">
                        <label>To</label>
                        <div class="inputAirport">
                            <input type="text" placeholder="City/Airport" id="arrivalAirport" class="search-airport" autocomplete="off" required="required" onclick="this.setSelectionRange(0, this.value.length)">
                            <input type="hidden" name="data[arrivalAirport]" class="hidden-input-code">
                        </div>
                    </div>
                </div>
                <div class="ticketCalendar clear-fix">
                    <div class="departureDate">
                        <label>Depart</label>
                        <div class="inputDate">
                            <input type="text" placeholder="DD.MM.YY" id="departureDate" required="required">
                            <input type="hidden" name="data[departureDate]" class="departureDateSendler">
                        </div>
                        <div class="calendar-content" id="departureDateCalendar"><div class="pika-single is-hidden is-bound" style="position: static; left: auto; top: auto;"></div></div>
                    </div>
                    <div class="arrivalDate">
                        <label>Return</label>
                        <div class="inputDate">
                            <input type="text" placeholder="DD.MM.YY" id="arrivalDate">
                            <input type="hidden" name="data[arrivalDate]" class="arrivalDateSendler">
                        </div>
                        <div class="calendar-content" id="arrivalDateCalendar"><div class="pika-single is-hidden is-bound" style="position: static; left: auto; top: auto;"></div></div>
                    </div>
                </div>
            </div>

            <div class="ticketPassengers clear-fix">
                <div class="amountPassengers">
                    <div class="selectBoxs">
                        <label for="adultSelect">Adults</label>
                        <select name="data[adult]" class="selectAmout" id="adultSelect">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                    <div class="selectBoxs">
                        <label for="kidsSelect">Kids</label>
                        <select name="data[kids]" class="selectAmout" id="kidsSelect">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                    <div class="selectBoxs">
                        <label for="babySelect">Babies</label>
                        <select name="data[baby]" class="selectAmout" id="babySelect">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>
                <div class="vline"></div>
                <div class="ticketGrade">
                    <h4>Cabin Class</h4>
                    <input type="radio" name="data[grade]" value="1" id="economClass" checked="">
                    <label for="economClass" class="ticketGradeLabel">
                        <span></span>
                        Economy                            </label>
                    <input type="radio" name="data[grade]" value="3" id="buisnessClass">
                    <label for="buisnessClass" class="ticketGradeLabel">
                        <span></span>
                        Business                            </label>
                </div>
            </div>
        </form>
    </div>
    <div class="termsAndButton clear-fix">
        <input type="submit" value="Search" id="submitSearch" class="submitSearch">
    </div>

</div>