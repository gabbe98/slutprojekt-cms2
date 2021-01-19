<?php

/** 
 * Plugin Name: Form
 */

require 'form-acf.php';

class form
{
  public function __construct()
  {
    add_action('kontakt', [$this, 'formdata'], 20);
    add_action('init', [$this, 'messages']);
    add_action('wp_ajax_kontaktformular', [$this, 'get_posts']);
  }

  public function formdata()
  { ?>
    <div class="container pb-3">
      <form class="my-4" action="
      <?php echo admin_url('admin-ajax.php');
      echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <h1>Kontaktformulär</h1>
          <div class="form-group">
              <label for="exampleInputEmail1">Email address</label>
              <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Skriv in din email">
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
              <textarea name="message" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
          </div>
          <div class="form-group">
              <label for="exampleFormControlFile1">Fil input</label>
              <input type="file" class="form-control-file" id="exampleFormControlFile1">
          </div>
          <button type="submit" class="btn btn-primary">Skicka in</button>
          <input type="hidden" name="action" value="kontaktformular">
      </form>
    </div>

    <?php
    if (isset($_REQUEST['sent'])) {
      echo '<h2>Tack för ditt meddelande.</h2>';
    }
    ?>
<?php }
  public function messages()
  {
    register_post_type('meddelanden', [
      'labels' => [
        'name' => __('Meddelanden'),
        'singular_name' => __('Meddelande')
      ],
      'public' => true,
      'has_archive' => true
    ]);
  }
  public function get_posts()
  {
    $post_id = wp_insert_post(array(
      'post_title' => $_REQUEST['email'],
      'post_content' => $_REQUEST['message'],
      'post_type' => 'Meddelanden',
      'tax_input' => array( 
        'category' => array( 
          'Meddelande', 
          'Reklamation', 
          'Faktura' 
        ) 
      )
    ));

    update_post_meta($post_id, 'email', $_REQUEST['email']);

    wp_redirect($_SERVER['HTTP_REFERER'] . '?sent=true');
    die();
  }
}

$newform = new form();
?>
