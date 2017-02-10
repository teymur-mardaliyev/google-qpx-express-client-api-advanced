<div class="search-box clear-fix">
    <div class="ticketParametrs left-contnet">
        <?php $this->loadTemplate(array("tickets/left-side-views")); ?>
    </div>

    <div class="right-content">

        <div class="search-waiter">
            <h1 class="search-title">Something went wrong.</h1>
            <div class="error-messages">
                <?php
                $this->renderFeedbackMessages();
                ?>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    var runGoogleMap = false;
    var searchStart = false;
</script>