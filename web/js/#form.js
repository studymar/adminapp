/* 
 * Funktionen für das Hauptmenü
 */


    /**
     * Aufklappmenü bei Klick offen halten
     * - fügt die class menuClicked zum parent hinzu (muss in css visibility 'visible' sein
     *
    $(".datepicker").datepicker({
        dateFormat: "d.m.Y",
        //format: "dd.mm.yyyy"
        weekNumbers: true
    });
    */

    $('form input:radio[name="LinkItem[type]"]').on('click', function(event){
   //$('form #Linktype-extern').on('click', function(event){
        if($('#Linktype-extern:checked').val() == 0){
            $('#Linktype-extern-form').show();
        }
        else {
            $('#Linktype-extern-form').hide();
        }
    });
  
    /**
     * Öffnet Content2 mit dem Page-Select und laedt dessen Inhalt neu
     * - Kann auch aus Page-Select heraus beim Abschicken des Filters genutzt werden
     *   (schickt dabei den Filter beim neuladen der Seite mit)
     * - data-saveurl muss als Parameter im Link gesetzt sein mit der URL
     *   des Page-Select
     */
    $('.Linktype-intern-select-link').on('click', function(event){
        loadToContent2(this.dataset.url,{
            'PageFilterForm[searchstring]': $('#searchstringInput').val()
        });
        event.preventDefault();
        return false;
    });
    
    /**
     * Öffnet Content2 mit dem Documentmanager und laedt dessen Inhalt neu
     * - Kann auch aus Documentmanager heraus beim Abschicken des Filters genutzt werden
     *   (schickt dabei den Filter beim neuladen der Seite mit)
     * - data-saveurl muss als Parameter im Link gesetzt sein mit der URL
     *   des Documentmanager
     */
    $('.Documentmanager-link').on('click', function(event){
        loadToContent2(this.dataset.url,{
            'PageFilterForm[searchstring]': $('#searchstringInput').val()
        });
        if($(this).hasClass('opener')){
            console.log('opening');
            //Vorhandene Docs in Documentmanager als ausgewählt übernehmen
            /*
            $('.selected .docItem .btn-primary').each(function(  ) {
                
            },
            $('.document-manager .after-select .docItem').each(function(  ) {
                $docItem = $('.document-manager .selected .docItem.demo').clone();
                $docItem.removeClass('demo');
                $docItem.find('.icon').addClass('');//icon class
                $docItem.find('.icon').attr('href','');//href
                $docItem.find('.openLink').attr('href','');//href
                $docItem.find('.openLink').html = ''; //headline
                $docItem.find('.infos').html = ''; //info (size)
                $docItem.find('.selectLink').attr('href',''); //href
                $docItem.find('.selectLink').attr('data-class',''); //extensionname
                $docItem.find('.selectLink').attr('data-filename',''); //filename
                $docItem.find('.selectLink').attr('data-name',''); //name
                $docItem.find('.selectLink').attr('data-id',''); //id
                $docItem.find('.selectLink').attr('data-size',''); //size
                $docItem.find('.selectLink').attr('data-created',''); //created
                $docItem.find('.selectLink').attr('data-createdBy',''); //createdBy
                $docItem.find('.btn-light').attr('href',''); //href
                
                
            },
            $docItem = $('.document-manager .docItem.demo').clone();
            $docItem.removeClass('demo');
            $docItem.find('input').val(this.dataset.id);
            $docItem.find('icon').addClass(this.dataset.class);
            $docItem.find('a').attr('href', this.dataset.filename);
            $docItem.find('div > a').html(this.dataset.name);
            $docItem.find('.infos .size').html(this.dataset.size);
            $docItem.find('.infos .created').html(this.dataset.created);
            $docItem.find('.infos .createdBy').html(this.dataset.createdBy);
            $($docItem).insertBefore('.document-manager .after-select .documentmanager-link');
            */
        }
        event.preventDefault();
        return false;
    });
    
    
    /**
     * Link zum entfernen einer noch nicht gespeicherten Verlinkung
     * blendet nur die Auswahl aus und zeigt den Bereich ohne Verlinkug wieder 
     * ein
     */
    $('#after-select-remove-link').on('click', function(event){
        $('.after-select').hide();        
        $('.radios').show();
        event.preventDefault();
        return false;
    });
    
    /**
     * Laedt eine Url mit POST-Parameter in den Content2-Bereich
     * und öffnet diesen
     */
    function loadToContent2(url, params){
        $('#content1').hide();
        $('#content2').show();
        $.post(url, params, function(data) {
            $('#content2').html(data);
        });        
    }
    
    /**
     * Öffnet den Content1-Bereich
     */
    function showContent1(){
        $('#content2').hide();
        $('#content1').show();        
    }

    