
// Prévention d'un double envoi si plusieurs images sont envoyées à la suite
var upAjaxChanged = {'{{ID}}': 0}

$('#{{ID}}').on('change', function(e) {
  
  // Event preventions
  if (upAjaxChanged['{{ID}}']) {
      return
  }
  upAjaxChanged['{{ID}}'] = 1
  e.preventDefault();

  // Progress Bar (conteneur)
  var pbc = $('#{{ID}}p');

  // Progress Bar
  var pb = pbc.children('div');

  // Element
  var e = $('#{{ID}}');
  
  // Element Container
  var ec = e.parent('div');

  // Result Div
  var rd = $('#{{ID}}r');

  // Remplissage du formdata
  var fd = new FormData();
  fd.append("{{ID}}", e[0].files[0]);

  // Upload
  $.ajax({

    // Progress
    xhr: function() {
      var xhr = new window.XMLHttpRequest();
      ec.hide()
      pbc.css('display', 'inline-block')
      xhr.upload.addEventListener('progress', function(evt) {
        if (evt.lengthComputable) {
          var percentComplete = evt.loaded / evt.total
          percentComplete = parseInt(percentComplete * 100)
          pb.width(percentComplete + '%')
          pb.text(percentComplete + '%')
//          if (percentComplete === 100) {
//          }
        }
      }, false);
      return xhr;
    },
    
    // Call
    url: '{{URL}}',
    type: 'POST',
    data: fd,
    contentType: false,
    processData: false,
    success: function(result) {
      
      // Réinitialisation de la barre de progression
      pbc.hide()
      pb.width(0)
      pb.html('')

      // S'il y a quelque chose à afficher...
      if (result) {
        
        // Affichage du response div
        rd.html(result)
        rd.css('display', 'inline-block')
        
        // Suppression du file input, car si celui-ci est renvoyé via la 
        // requête ajax, il apparaîtra en double et ne fonctionnera plus
        ec.html('')
        
        // S'il y a un script js à exécuter dans la requête ajax, l'exécuter
        // (le script d'autoreload du nouveau bouton ou une redirection)
        var localScript = rd.children('script')
        if (localScript) {
          $.globalEval(localScript.html())
          localScript.remove();
        }
      }
      
      // S'il n'y a rien à afficher, on réaffiche le file input par défaut
      else {
        ec.css('display', 'inline-block')
      }
    },
    
    // S'il y a une erreur, afficher un message dans le response div 
    error: function(e) {
      pbc.hide()
      var errorTxt = 'Une erreur est survenue';
      if (e.status == 404) {
        errorTxt = 'Err: connexion trop lente ou coupée'
      }
      rd.html('<div class="text-danger">' + errorTxt + '</div>')
    }
  });
});
