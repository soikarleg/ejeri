<?php
session_start();
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];

use benhall14\phpCalendar\Calendar;

$calendar = new Calendar();
$calendar
    ->addEvent(date('Y-10-12'), date('Y-10-13'), 'Chantier #1', true)
    ->addEvent(date('Y-10-23'), date('Y-10-23'), 'Chantier #2', true)
    ->addEvent(date('Y-11-12'), date('Y-11-13'), 'Chantier #1', true)
    ->addEvent(date('Y-11-23'), date('Y-11-23'), 'Chantier #2', true)
    ->addEvent(date('Y-12-25'), date('Y-12-25'), 'Noël', true);
#   or
/*
$events = array(
    array(
        'start' => date('Y-01-14'),
        'end' => date('Y-01-14'),
        'summary' => 'My Birthday',
        'mask' => true
    ), 
    array(
        'start' => date('Y-12-25'),
        'end' => date('Y-12-25'),
        'summary' => 'Christmas',
        'mask' => true
    )
);
$calendar->addEvents($events);
*/
?>
<div class="scroll mb-2">
    <div class="row fix">
        <div class="col-xs-3 col-sm-3 col-md-3 mb-4">
            <p>Liste des devis acceptés</p>
            <p>Interventions à placer</p>
        </div>
        <div class="col-xs-9 col-sm-9 col-md-9 mb-4">
            <?php
            $annee = date('Y');
            $mois = date('m');
            $startDate = strtotime("$annee-$mois-01");
            $currentYear = date("Y", $startDate);
            $currentMonth = date("n", $startDate);
            // Boucle for pour afficher les mois à partir de la date de départ
            for ($i = $currentMonth; $i <= 12; $i++) {
            ?>
                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                    <?php
                    $calendar->useFullDayNames();
                    // $calendar->hideSundays();
                    // $calendar->hideSaturdays();
                    $calendar->useMondayStartingDate();
                    echo $calendar->draw(date('Y-' . $i . '-1'), 'grey'); ?>
                </div>
            <?php
            }
            ?>
        </div>
        <!-- <div class="col-xs-12 col-sm-6 col-md-6">
            <?php echo $calendar->draw(date('Y-2-1'), 'pink'); ?>
            <hr />
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <?php echo $calendar->draw(date('Y-3-1'), 'blue'); ?>
            <hr />
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <?php echo $calendar->draw(date('Y-4-1'), 'orange'); ?>
            <hr />
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <?php echo $calendar->draw(date('Y-5-1'), 'purple'); ?>
            <hr />
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <?php echo $calendar->draw(date('Y-6-1'), 'yellow'); ?>
            <hr />
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <?php echo $calendar->draw(date('Y-7-1'), 'green'); ?>
            <hr />
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <?php echo $calendar->draw(date('Y-8-1'), 'grey'); ?>
            <hr />
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <?php echo $calendar->draw(date('Y-9-1'), 'pink'); ?>
            <hr />
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <?php echo $calendar->draw(date('Y-10-1'), 'blue'); ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <?php echo $calendar->draw(date('Y-11-1'), 'orange'); ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <?php echo $calendar->draw(date('Y-12-1'), 'purple'); ?>
        </div> -->
    </div>
</div>
<div class="border-dot p-1 mb-4">
    <p>&copy; Copyright Benjamin Hall :: <a href="https://github.com/benhall14/php-calendar">https://github.com/benhall14/php-calendar</a></p>
</div>