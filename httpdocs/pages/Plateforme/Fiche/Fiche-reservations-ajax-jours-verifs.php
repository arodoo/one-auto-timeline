<?php
				$heure = explode(':', $heure);
				$heure_h = $heure['0'];
				$heure_h_s = ($heure['0']*60*60);
				$heure_m_s = ($heure['1']*60);
				$horaire_seconde = ($heure_h_s+$horaire_seconde+$heure_m_s);

				$date = explode('-', $date);
				$date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));

				$horaire_seconde_total = ($duree_prestation_seconde+$horaire_seconde+mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time())));
				//echo " ".mktime($heure[0],$heure[1],$heure[2])." -- $horaire_seconde_total -- $horaire_seconde --";

				$date_debut_seconde = ($date+$horaire_seconde);
				$date_fin_seconde = ($date_debut_seconde+$duree_prestation_seconde);

				$date = "".$_POST['date']." ".$_POST['heure']."";
				$date_fin = "".$_POST['date']." ".$_POST['heure']."";

				$debut = new DateTime($date);
				$fin = new DateTime($date_fin);
				$date_debut = date('Y-m-d H:i', $date_debut_seconde);
				$date_fin = date('Y-m-d H:i', $date_fin_seconde);

				$date_debut_jours = date('w', $date_debut_seconde);

				$sql_horaire = $bdd->prepare("SELECT * FROM membres_etablissements_horaires WHERE id_etablissement=?");
				$sql_horaire->execute(array($resa['id_etablissement'])); 
				$horaire = $sql_horaire->fetch();                   
				$sql_horaire->closeCursor();
				$horaire_semaine_matin_debut = explode(':', $horaire['horaire_semaine_matin_debut']);
				$horaire_semaine_matin_fin = explode(':', $horaire['horaire_semaine_matin_fin']);
				$horaire_semaine_apresmidi_debut = explode(':', $horaire['horaire_semaine_apresmidi_debut']);
				$horaire_semaine_apresmidi_fin = explode(':', $horaire['horaire_semaine_apresmidi_fin']);
				$horaire_dimanche_debut = explode(':', $horaire['horaire_dimanche_debut']);
				$horaire_dimanche_fin = explode(':', $horaire['horaire_dimanche_fin']);
				$horaire_samedi_debut = explode(':', $horaire['horaire_samedi_debut']);
				$horaire_samedi_fin = explode(':', $horaire['horaire_samedi_fin']);

				if($date_debut_jours == 0 && $horaire['ouvert_dimanche'] == "oui"){
					$ouvert = "oui";
					if(mktime($horaire_dimanche_debut[0],$horaire_dimanche_debut[1],$horaire_dimanche_debut[2]) <= $horaire_seconde_total 
					&& mktime($horaire_dimanche_fin[0],$horaire_dimanche_fin[1],$horaire_dimanche_fin[2]) >= $horaire_seconde_total ){ $heure_ok = "oui"; }

				}elseif( $date_debut_jours == 1 && $horaire['ouvert_lundi'] == "oui" || $date_debut_jours == 2 && $horaire['ouvert_mardi'] == "oui" || $date_debut_jours == 3 && $horaire['ouvert_mercredi'] == "oui" || $date_debut_jours == 4 && $horaire['ouvert_jeudi'] == "oui" || $date_debut_jours == 5 && $horaire['ouvert_vendredi'] == "oui" ){
					$ouvert = "oui";
					if(mktime($horaire_semaine_matin_debut[0],$horaire_semaine_matin_debut[1],$horaire_semaine_matin_debut[2]) <= $horaire_seconde_total 
					&&  mktime($horaire_semaine_matin_fin[0],$horaire_semaine_matin_fin[1],$horaire_semaine_matin_fin[2]) >= $horaire_seconde_total 
					|| mktime($horaire_semaine_apresmidi_debut[0],$horaire_semaine_apresmidi_debut[1],$horaire_semaine_apresmidi_debut[2]) <= $horaire_seconde_total 
					&& mktime($horaire_semaine_apresmidi_fin[0],$horaire_semaine_apresmidi_fin[1],$horaire_semaine_apresmidi_fin[2]) >= $horaire_seconde_total  ){ 
						$heure_ok = "oui"; 
					}

				}elseif($date_debut_jours == 6 && $horaire['ouvert_samedi'] == "oui"){
					$ouvert = "oui";
					if(mktime($horaire_samedi_debut[0],$horaire_samedi_debut[1],$horaire_samedi_debut[2]) <= $horaire_seconde_total 
					&& mktime($horaire_samedi_fin[0],$horaire_samedi_fin[1],$horaire_samedi_fin[2]) >= $horaire_seconde_total ){ $heure_ok = "oui"; }

				}
?>