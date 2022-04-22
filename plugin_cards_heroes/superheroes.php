<?php
/*
Plugin Name: Cards SuperHeroes
Plugin URI: https://superheroapi.com
Description: display super heroes on cards using a shortcode
Version: 1.0
Author: David Ortiz Rincon
Author URI: https://david2018ortiz.github.io/
*/

//we insert our style sheet with its respective priority
function styles_lot()
{
    wp_register_style( 'style', plugins_url( '/css/style.css', __FILE__ ), array(), '99999', 'all' );
    wp_enqueue_style( 'style' );
}
add_action( 'wp_enqueue_scripts', 'styles_lot' );

//prepare the buffered function
ob_start();
//we declare our function with which we will call from the API the data of the superheroes
function displaySuper($superhero)
{
    $hero = shortcode_atts( array (
        'name' => $superhero
        ), $superhero);
    $request = wp_remote_get("https://superheroapi.com/api/10226244192914722/search/".$hero['name']);
    $response = wp_remote_retrieve_body($request);
    $objectData = json_decode( $response );
    echo '<div class="mainSuper">';
    for ($i=0; $i < count($objectData->results); $i++) {        
        echo '<div class="cardSuper" id="cardSuper'.$i.'">';
        echo '<div class="imgSuper"> <img id="imgSuper" src='.$objectData->results[$i]->image->url.'></div>';
        echo '<h2 id="name">'.$objectData->results[$i]->name.'</h2>';        
        echo '<h3 id="skills"> Skills </h3>';
        echo '<ul id="skilsSuper">';
        echo '<li> Intelligence: <b>'.$objectData->results[$i]->powerstats->intelligence.'</b></li>';
        echo '<li> Strength: <b>'.$objectData->results[$i]->powerstats->strength.'</b></li>';
        echo '<li> Speed: <b>'.$objectData->results[$i]->powerstats->speed.'</b></li>';
        echo '<li> Durability: <b>'.$objectData->results[$i]->powerstats->durability.'</b></li>';
        echo '<li> Power: <b>'.$objectData->results[$i]->powerstats->power.'</b></li>';
        echo '<li> Combat: <b>'.$objectData->results[$i]->powerstats->combat.'</b></li>';
        echo '</ul>';
        echo '<h4 id="titleBiography"> Biography </h4>';
        echo '<p id="biography"> Your name is: '.$objectData->results[$i]->biography->{'full-name'}.'</br>
              Place of birth: '.$objectData->results[$i]->biography->{'place-of-birth'}.'</br>
              First appearance: '.$objectData->results[$i]->biography->{'first-appearance'}.'</p>';
        echo '</div>';        
    }
    echo '</div>';
//we clean the buffer where we preload the function
    $output= ob_get_clean();
    return $output;
}

//we create and name our shortcode and the function that will be executed when it is used in any log of the site
add_shortcode('superhero', 'displaySuper');

?>
