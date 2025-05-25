<?php
//session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
// $term = null;
// $verifier_client = verifInfosClient($secteur);
$con = new connBase();
// $reqevents = "SELECT * FROM `events` WHERE idcompte = '$secteur'";
// $events = $con->allRow($reqevents);
// echo $fullevents =  json_encode($events);
?>
<p class="titre_menu_item mb-2">Planning des interventions</p>

<script>
  $(document).ready(function() {

    var today = new Date();
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var day = String(today.getDate()).padStart(2, '0');
    var formattedDate = year + '-' + month + '-' + day;
    var visibleDate = day + '-' + month + '-' + year;

    var containerEl = document.getElementById('external-events-list');
    new FullCalendar.Draggable(containerEl, {
      itemSelector: '.fc-event',
    });

    var calendarEl = $('#calendar');
    var calendar = new FullCalendar.Calendar(calendarEl[0], {
      contentHeight: 650,
      showNonCurrentDates: true,
      daysOfWeek: [1, 2, 3, 4, 5],
      hiddenDays: [0, 6],
      slotMinTime: '07:00:00',
      slotMaxTime: '19:00:00',
      weekNumbers: true,
      fixedWeekCount: false,
      businessHours: true,
      nowIndicator: true,
      headerToolbar: {
        left: 'title',
        center: '',
        right: 'today prevYear,prev,next,nextYear dayGridMonth,timeGridWeek,listWeek' // user can switch between the two
      },
      initialView: 'dayGridMonth',
      editable: true,
      navLinks: true,
      selectable: true,
      businessHours: true,
      dayMaxEvents: true,
      handleWindowResize: true,
      events: '/src/pages/calendar/event_fetch.php',
      googleCalendarApiKey: 'AIzaSyAzGUJKT4T52o_HtWDDqGoesLKFyJyiOQE',

      // eventSourceSuccess: function(content, response) {
      //   console.log('Events Calendar :', content);
      // },
      // eventSourceFailure: function(error) {
      //   console.error(error);
      // },

      eventSources: [
        //{
        //     events: '/src/pages/calendar/event_fetch.php',
        //   },
        // {
        //   googleCalendarId: 'dvno8ofbgqjg8g2hh3aq6732rc@group.calendar.google.com',
        // },
        {
          googleCalendarId: 'fr.christian#holiday@group.v.calendar.google.com',
        },
      ],
      eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        meridiem: false
      },
      locale: 'fr',
      droppable: true,

      eventContent: function(info) {
        console.log('info eventContent ******');
        console.log(info.event.extendedProps);
        let title = info.event.title;
        let start = info.event.start;
        let description = info.event.extendedProps.description;
        let iduser = info.event.extendedProps.iduser;

        return {
          html: `<div class="small text-white text-bold"><span class="puce-mag">${iduser}</span> ${timeStr(start)} ${title}<p class="small text-right">${description}</p> </div>`
        };
      },

      eventClick: function(info) {
        console.log('info eventClick ****************');
        console.log(info);
        var title = info.event.title;
        var date = dateStr(info.event.start);
        var start = timeStr(info.event.start);
        var end = timeStr(info.event.end);
        var description = info.event.extendedProps.description;
        var initiales = info.event.extendedProps.iduser;
        $('#eventDate').val(date);
        $('#client').val(title);
        $('#debut').val(start);
        $('#fin').val(end);
        $('#description').val(description);
        $('#initiales').val(initiales);
        $('#eventModal').modal('show');
      },

      dateClick: function(info) {
        var date = info.dateStr;
        const mydate = date.split('-');
        let visibleDate = mydate[2] + '/' + mydate[1] + '/' + mydate[0];
        console.log(info);
        $('#eventDateHidden').val(info.dateStr); // Stocker la date sélectionnée dans le champ caché
        $('#eventDate').val(visibleDate);
        $('#eventModal').modal('show'); // Afficher la modale

      },
      drop: function(info) {
        let des = '';
        let eventstr = info.draggedEl.firstElementChild.dataset.event;
        console.log('event ******************');
        console.log(eventstr);
        const event = JSON.parse(eventstr);
        console.log('description ******************');
        console.log(event.description);
        des = (event.description === undefined || event.description === null) ? 'Prestation à définir' : event.description;
        console.log(des);
        calendar.addEvent({
          title: event.title,
          iduser: event.iduser,
          duration: event.duration,
          extendedProps: {
            description: des,
          },
          start: info.dateStr + ' ' + event.start,
          end: info.dateStr + ' ' + event.end,
          allDay: false
        });
        console.log('drop ********************');
        console.log(info);
        if (document.getElementById('drop-remove').checked) {
          info.draggedEl.parentNode.removeChild(info.draggedEl);
        }
      }
    });

    calendar.setOption('locale', 'fr');
    calendar.render();

  });
</script>

<div class="modal fade" id="eventModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Prestations</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="eventItems">
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Date</span>
            <input type="text" class="form-control" id="eventDate" name="date" value="">
            <input type="hidden" id="eventDateHidden" date="date_h" value="">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Intervenant</span>
            <input type="text" class="form-control" id="initiales" name="initiales" value="">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Client</span>
            <input type="text" class="form-control" id="client" name="client" value="">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Description</span>
            <input type="text" class="form-control" id="description" name="description" value="">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Début</span>
            <input type="time" class="form-control" placeholder="Heure debut" id="debut" name="debut">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Fin</span>
            <input type="time" class="form-control" placeholder="Heure fin" id="fin" name="fin">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-mag-n mr-2" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-mag-n text-success" onclick="ajaxEvent('#eventItems');">Modifier la prestation</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php
$reg = $con->askClientRegulier($secteur);

?>

<div class="">
  <div class="row">
    <div class="col-md-3">
      <div id='external-events mt-4'>
        <div id='external-events-list' class="scroll">

          <?php
          foreach ($reg as $regu) {
            ${$key} = $value;
            $nom = strtolower(NomCli($regu['idcli']));
            $nom =
              ucfirst($nom);
            $nom_complet =  NomClient($regu['idcli']);
            $idcli = $regu['idcli'];


          ?>

            <div class='fc-event puce-mag mb-2 p-1'>
              <div class='fc-event-main move' data-event='{ "title": "<?= $nom ?>", "start":"08:00","end":"10:00","iduser":"<?= initialesColla($iduser) ?>", "duration":"02:00", "allDay":"false" }'><?= $nom_complet ?></div>
            </div>

          <?php
          }
          ?>
        </div>

        <p>
          <input type='checkbox' id='drop-remove' />
          <label for='drop-remove'>Effacer après le 'drop'</label>
        </p>
      </div>
    </div>
    <div class="col-md-9">
      <div id="calendar"></div>


    </div>
    <div class="col-md-12">

    </div>
  </div>
</div>