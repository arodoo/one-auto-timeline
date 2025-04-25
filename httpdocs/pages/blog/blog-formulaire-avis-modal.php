<!-- Modal -->
<div class="modal" id="deposer-un-avis-en-ligne" role="dialog" aria-hidden="true" style='z-index:9999; text-align: center;'>
  <div class="modal-dialog" style='z-index:9999;'>
    <div class="modal-content">
	<!-- FORMULAIRE VALIDATION -->
      <div class="modal-header" style='text-align: left;'>
        <h2 class="modal-title" style="text-transform: uppercase; font-size: 20px;" ><button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-left: 0px;" >&times;</button><?php echo "Ajouter un avis pour l'article"; ?></h2>
      </div>
      <div class="modal-body" style="text-align:left;" >
<?php
include('pages/blog/blog-commentaires.php');
?>
	</div>
      </div>
    </div>
