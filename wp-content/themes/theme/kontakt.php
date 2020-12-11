<?php
/*
*Template name: Kontakt
*/

get_header();

?>

<div class="container pb-5">
    <form class="my-5">
        <h1>Kontaktformulär</h1>
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Skriv in din email">
            <small id="emailHelp" class="form-text text-muted">Vi delar aldrig ut din email till andra.</small>
        </div>
        <div class="form-group">
            <label for="exampleFormControlSelect1">Vad handlar det om?</label>
            <select class="form-control" id="exampleFormControlSelect1">
            <option>Kontakt</option>
            <option>Reklamation</option>
            <option>Faktura</option>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Skriv ett meddelande</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="exampleFormControlFile1">Fil input</label>
            <input type="file" class="form-control-file" id="exampleFormControlFile1">
        </div>
        <button type="submit" class="btn btn-primary">Skicka in</button>
    </form>

<?php

$map = get_field('google_maps');

echo '<img src="https://maps.googleapis.com/maps/api/staticmap?center=' . $map['lat'] . ',' . $map['lng'] . '&zoom=15&size=600x300&maptype=roadmap
&markers=color:red%7C' . urlencode($map['address']) . 
'&key=AIzaSyBQV3hynMzQFauvPmW6-RrMlL4UwjpMQaI" class="mb-3">';

echo '<p><b>Address:</b> <u>' . $map['address'] . '</u></p>';
echo '<p><b>Telefonnummer:</b> <u>' . get_field('telefonnummer')  . '</u></p>';
echo '<p><b>Mejl:</b> <u>' . get_field('mejl')  . '</u></p>';
the_content();
?>

</div>

<?php

wp_footer();