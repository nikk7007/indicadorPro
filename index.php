<?php 
include "config/connect.php";
include "header.php";

?>

<script>

function saveCoords(geo) {

    return {lat: geo.coords.latitude, lon: geo.coords.longitude};
}
navigator.geolocation.getCurrentPosition(saveCoords, saveCoords)

</script>