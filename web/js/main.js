$(document).ready(function() {

    // Datetimepicker
    $('.datepicker').datepicker();

    var container = $('#sdz_blogbundle_article_categories');

    function add_category() {
        index = container.children().length;

        template = container.attr('data-prototype');
        template = template.replace(/__name__/g, index);

        deleteCategoryLink = '<a href="#" id="delete_category_' + index + '" class="btn btn-danger">Supprimer</a><br /><br />';
        container.append(deleteCategoryLink);

        $('#delete_category_' + index).click(function() {
            $(this).prev().remove();
            $(this).remove();

            return false;
        });

        if (index == 0) {
            add_category();
        }

        $('#add_category').click(function() {
            add_category();

            return false;
        });
    }

    // Gestion des commentaires (comments)
    var div_comments = $('#sdz_blogbundle_article_commentaires');

    function add_comments() {
        index = div_comments.children().length;
        index = index - nbRemoveLink;
        // console.log(index);
        template = div_comments.attr('data-prototype');
        // template_modify = template.replace(/__name__/g, index);
        // console.log(template);
        // template = template.replace(/__name__label__/g, 'Commentaire ' + index);
        template = template.replace(/__name__label__/g, '');
        // On ajoute le contenu de l'attribut data-prototype à notre balise div après avoir remplacé la quantité __name__ par index
        div_comments.append(template.replace(/__name__/g, index));

        deleteCommentLink = '<a href="#" id="delete_comment_' + index + '" class="btn btn-danger">Supprimer</a>';
        div_comments.append(deleteCommentLink);

        nbRemoveLink++;

        // Gestionnaire event delete comment
        $('#delete_comment_' + index).click(function() {
            $(this).prev().remove();
            $(this).remove();
            nbRemoveLink--;

            return false;
        });
    }

    if (div_comments.children().length == 0) {
        nbRemoveLink = 0;
        add_comments();
    }

    // Gestionnaire d'event ajout comments
    $('#add_comments').click(function() {
        alert(div_comments.children().length);

        add_comments();

        return false;
    });

});