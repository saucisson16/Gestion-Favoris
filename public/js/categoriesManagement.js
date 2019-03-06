$(document).ready(function () {
    
    //MESSAGE
    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgManageCategories(type, message) {
        // construit le html pour le message flash
        var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgManageCategories').html(appendCode);
        if(type != 'danger') {
            // efface le message flash apres 5 secondes
            function removeFlashMsg(){
                $('.flash-msg').replaceWith("");
            }
            setTimeout(removeFlashMsg, 5000);
        }
    }

    //CREATION D'UNE CATEGORIE
    createCategory();
    //définition de la fonction pour l'appliquer récursivement
    function createCategory() {
        $('form[name="create_category"]').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var url = $('.btn-user-create-category').attr('url');
            $.ajax({
                type: 'POST',
                url: url,
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (data){
                    reloadCategoriesTableAfterAddingOrModifying();
                    reloadWritePost();
                    $('#create_category_name').val('');
                    $('#create_category_photoPath').val('');
                    addFlashMsgManageCategories('success', data);
                },
                error: function (jqxhr) {
                    addFlashMsgManageCategories('danger', jqxhr.responseText);

                }
            })
        })
    }

    // EDITION D'UNE CATEGORIE
    editCategory();

    // fonction permettant de traiter la modification d'une categorie
    function editCategory() {
        $('.btn-edit-category').on('click', function edit(e){
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    // ajoute la modale d'edition
                    $a.parent().prepend(data);
                    $('#update_category_save').css('margin-top', '10px');
                    $a.css('margin-right', '30px');
                    // affiche la modale d'édition
                    $a.prev().modal('show');
                    // en cas de fermeture  de la modale sans modification
                    $a.prev().on('hidden.bs.modal', function (e) {
                        // supprime la modale d'édition
                        $a.prev().replaceWith('');
                    });
                    // A la soumission du formulaire d'édition
                    $('form[name="update_category"]').on('submit', function(e){
                        e.preventDefault();
                        var $form = $(this);
                        url = $a.attr('href');
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: new FormData(this),
                            processData: false,
                            contentType: false,
                            success: function (data, text, jqxhr) {
                                // ajoute un message flash
                                var appendCode = '<div class="flash-msg alert alert-success">Catégorie mise à jour</div>';
                                $form.parent().prepend(appendCode);
                                // efface le message flash apres 5 secondes
                                function removeFlashMsg(){
                                    $('.flash-msg').replaceWith("");
                                }
                                setTimeout(removeFlashMsg, 5000);
                                // a la fermeture de la modale
                                $a.prev().on('hidden.bs.modal', function (e) {
                                    // efface la modale d'édition
                                    $a.prev().replaceWith('');
                                    addFlashMsgManageCategories('success', 'Catégorie mise à jour');
                                    // retire l'ecoute de l'évenement clic sur un bouton edit
                                    $('.btn-edit-faq').off('click', edit);
                                });
                                $a.prev().modal('hide');
                            },
                            error: function (jqxhr) {
                                var appendCode = '<div class="flash-msg alert alert-danger">'+jqxhr.responseText+'</div>';
                                $form.parent().prepend(appendCode);
                                // efface le message flash apres 5 secondes
                                function removeFlashMsg(){
                                    $('.flash-msg').replaceWith("");
                                }
                                setTimeout(removeFlashMsg, 15000);
                            }
                        })
                    })

                },
                error: function() {
                    addFlashMsgManageCategories('danger', "Une erreur est survenue")
                }
            });
        });
    }

    // SUPPRESSION DE LA CATEGORIE
    removeCategory();

    // fonction permettant de traiter la suppression d'une categorie
    function removeCategory() {
        $('.btn-rm-category').on('click', function(e) {
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type:'GET',
                url: url,
                success: function (data) {
                    // ajoute le message flash
                    addFlashMsgManageCategories('success', data);
                },
                error: function (jqxhr) {
                    addFlashMsgManageCategories('danger', "Une erreur est survenue")
                }
            })
        });
    }
});
