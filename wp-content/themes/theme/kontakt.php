<?php
/*
*Template name: Kontakt
*/

get_header();

do_action('kontakt')

?>


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

get_footer();