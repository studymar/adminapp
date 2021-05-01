/* 
 * Funktionen für das Hauptmenü
 */


    /**
     * Aufklappmenü bei Klick offen halten
     * - fügt die class menuClicked zum parent hinzu (muss in css visibility 'visible' sein
     */
    $('.keepOpenByClick').click(function(event){
        event.preventDefault();
        //console.log('menuClicked')
        event.target.parentElement.classList.toggle('menuClicked')
    });

    /**
     * Aufklappmenü bei Klick im Responsive Menu
     * - fügt die class menuClicked zum parent hinzu (muss in css visibility 'visible' sein
     */
    $('.menuOpenByClickResponsive').click(function(event){
        event.preventDefault();
        // console.log('menuOpenByClickResponsive')
        // Menu nach click einblenden oder ausblenden
        // console.log(event.target.classList);
        // eigenen Layer schließen, wenn ein anderes Ziel genannt ist (außer bei first = erste Layer-Öffnung)
        if(event.target.dataset.target && !event.target.classList.contains('first') ){
            event.target.parentElement.parentElement.classList.toggle('hide');
            event.target.parentElement.parentElement.classList.toggle('menuClicked');
        }
        // Ziel-Layer öffnen
        document.getElementById(event.target.dataset.target).classList.toggle('hide')
        document.getElementById(event.target.dataset.target).classList.toggle('menuClicked')
    });

    /*
    menuClickedResponsive: function (target, event, submenu) {
      // Submenu ablegen
      if(submenu)
          this.subnavigation = submenu
      // Menu nach click einblenden oder ausblenden
      event.target.parentElement.parentElement.classList.toggle('hide')
      event.target.parentElement.parentElement.classList.toggle('menuClicked')
      document.getElementById(target).classList.toggle('hide')
      document.getElementById(target).classList.toggle('menuClicked')
    },
    */
  
    
    
    