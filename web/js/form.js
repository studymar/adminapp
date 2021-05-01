/* 
 * Funktionen
 */


    /**
     * Confirm-Modal Parameter übergeben
     * - fügt den Wert aus dem Aufruf ins Modal ein
     *
    */
    $('a[data-target="#confirm-modal"], button[data-target="#confirm-modal"]').on('click', function(event){
        //Bezeichnung des zu löschenden Elements
        var data_name = '';
        if (typeof $(this).data('name') !== 'undefined') {
            data_name = $(this).data('name');
            $('#confirm-modal .deletename').html('"' + data_name + '"');
        }
        //URL die nach Confirm zum löschen aufgerufen werden soll
        var data_url = '';
        if (typeof $(this).attr('href') !== 'undefined') {
            data_url = $(this).attr('href');
            $('#confirm-modal #modal-confirmed-button').attr('href',data_url);
        }
        
    });
  


