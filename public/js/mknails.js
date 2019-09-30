$(function() { 

// AFFICHER LE NOM DU FICHIER SELECTIONNÉ

$('#galerie_file').on('change',function(){
    // Récupérer le nom du fichier selectionné puis filtrer grâce à une regex de sorte à masquer son chemin d'accès
    var fileName = $(this).val().replace(/.*[\/\\]/, '');

    // Afficher le nom du fichier dans le label de l'input
    $(this).next('.custom-file-label').html(fileName);
})

$('.picture').mouseenter(function() {
    $(this).find('.pic-title').show(); 
});

$('.picture').mouseleave(function() {
    $(this).find('.pic-title').hide(); 
});



});