!(function (n) {
    "use strict";
    function t() {
        this.$body = n("body");
    }
    (t.prototype.init = function () {
        (Dropzone.autoDiscover = !1),
            n('[data-plugin="dropzone"]').each(function () {
                var t = n(this).attr("action"),
                    i = n(this).data("previewsContainer"),
                    e = { 
                        url: t,
                        autoProcessQueue: false,  // Désactiver le traitement automatique ; on l'enclenchera au submit
                        clickable: true,          // Permettre le clic pour sélectionner des fichiers
                        createImageThumbnails: true,
                        maxFiles: 10,             // Limiter à 10 fichiers
                        parallelUploads: 5,       // Téléchargements parallèles
                        uploadMultiple: true,     // Permettre le téléchargement multiple
                        paramName: 'attachments[]', // Nom du champ fichier côté serveur
                        maxFilesize: 10,          // Taille maximale en MB
                        init: function() {
                            // Intercepter la soumission du formulaire pour lancer manuellement l'upload
                            var myDropzone = this;
                            var form = myDropzone.element;

                            // Éviter d'ajouter plusieurs listeners si plusieurs dropzones
                            if (!form.dataset.dzSubmitBound) {
                                form.addEventListener('submit', function(e) {
                                    if (myDropzone.getQueuedFiles().length > 0) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        myDropzone.processQueue();
                                    }
                                });
                                form.dataset.dzSubmitBound = 'true';
                            }

                            // Lorsque tous les fichiers ont été téléversés, soumettre le formulaire classique
                            myDropzone.on('queuecomplete', function () {
                                form.submit();
                            });
                        }
                    };
                i && (e.previewsContainer = i);
                var o = n(this).data("uploadPreviewTemplate");
                o && (e.previewTemplate = n(o).html());
                n(this).dropzone(e);
            });
    }),
        (n.FileUpload = new t()),
        (n.FileUpload.Constructor = t);
})(window.jQuery),
    (function () {
        "use strict";
        window.jQuery.FileUpload.init();
    })();
