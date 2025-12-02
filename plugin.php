<?php

class pluginLosungen extends Plugin {

	public function init()
	{
		$this->dbFields = array(
			'label'=>'Losung und Lehrtext für',
			'bibel-text-bold'=>'0',
			'bibel-text-link'=>'1',
			'date-format' => '2',
			"colon" => '0',
		);
	}

	public function form()
	{
		global $L;

		$html  = '<div class="alert alert-primary" role="alert">';
		$html .= $this->description();
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('Label').'</label>';
		$html .= '<input name="label" type="text" value="'.$this->getValue('label').'">';
		$html .= '</div>';
		// Bibeltext Bold
		$html .= '<div>';
		$html .= '<input type="hidden" name="bibel-text-bold" value="0" />';
		$html .= '<label>'.$L->get('bibel-text-bold').'</label>';
		$html .= '<input type="checkbox" name="bibel-text-bold" value="1" '.($this->getValue('bibel-text-bold') === "1"?'checked="checked"':''). '"/>';
		$html .= '</div>';
		// Bibeltext Link
		$html .= '<div>';
		$html .= '<label>'.$L->get('bibel-text-link').'</label>';
		$html .= '<input type="hidden" name="bibel-text-link" value="0" />';
		$html .= '<input type="checkbox" name="bibel-text-link" value="1" '.($this->getValue('bibel-text-link') === "1"?'checked="checked"':''). '"/>';
		$html .= '</div>';
		// Datum format
		$html .= '<div>';
		$html .= '<label>'.$L->get('date-format').'</label>';
		$html .= '<select name="date-format">';
		$html .= '<option value="0"' .($this->getValue('date-format')==="0"?'selected':'') .'>'.$L->get('date-format-0').'</option>';
		$html .= '<option value="1"' .($this->getValue('date-format')==="1"?'selected':'') .'>'.$L->get('date-format-1').'</option>';
		$html .= '<option value="2"' .($this->getValue('date-format')==="2"?'selected':'') .'>'.$L->get('date-format-2').'</option>';
		$html .= '<option value="3"' .($this->getValue('date-format')==="3"?'selected':'') .'>'.$L->get('date-format-3').'</option>';
		$html .= '</select>';
		$html .= '</div>';
		$html .= '<div>';
		$html .= '<label>'.$L->get('colon').'</label>';
		$html .= '<input type="hidden" name="colon" value="0" />';
		$html .= '<input type="checkbox" name="colon" value="1" '.($this->getValue('colon') === "1"?'checked="checked"':''). '"/>';
		$html .= '</div>';

		return $html;
	}

	public function siteSidebar()
	{
// =================================
// Script zum Einfügen der Losungen:
// =================================


// Einstellungen:
// ==============

// Bibeltext fett ausgeben: (1 = fett    0 = nicht fett)
$LphpBibeltextFett = $this->getValue('bibel-text-bold');

// Stellenangabe als Link zur Internetbibel: (1 = Link    0 = kein Link)
$LphpBibelLink = $this->getValue('bibel-text-link');

// Überschrift einfügen: ("" = keine Überschrift)
$LphpTitelText = $this->getValue('label');

// Datumsangabe allein oder hinter Überschrift:
$LphpTitelDatum = $this->getValue('date-format');

// mögliche Werte: (Beispiel 04.02.2008)
// 0 = (keine Datumsangabe)
// 1 = "04.02.2008"
// 2 = "Montag, 4. Februar 2008"
// 3 = "4. Februar 2008"

// Doppelpunkt hinter Überschrift / Datum (1 = Doppelpunkt    0 = keiner)
$LphpTitelDoppelpunkt = $this->getValue('colon');

$html  = '<div class="plugin plugin-about">';

// =================================================================
// Den nachfolgenden Code bitte nur ändern, wenn Sie sich auskennen!
// =================================================================

// Datendatei zum aktuellen Jahr ermitteln: 
$LphpDatei = PATH_PLUGINS."losungen".DS."dat".DS."losungphp" . date("Y") . ".dat";


// Die Daten aus der Datendatei einlesen:
$LphpFp = @fopen($LphpDatei,"rb");
if ($LphpFp){
	$LphpTagID = date("z") +1;
	fseek ($LphpFp, ($LphpTagID * 12) - 12);
	$LphpPoLa = fread($LphpFp, 12);
	$LphpPo = intval(substr($LphpPoLa, 0, 6)) -1;
	$LphpLa = intval(substr($LphpPoLa, 6, 6));
	fseek ($LphpFp, $LphpPo);
	$LphpText = fread($LphpFp, $LphpLa);
	$Lphp = explode("~", $LphpText);
	fclose($LphpFp);

	// Variablen für die Datumsangabe in der Überschrift
	// Wochentagsname: (z.B.: "Montag")
	$LphpWT = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
	$LphpWochentagName = $LphpWT[date("w")];

	// Monatsname: (z.B.: "Februar")
	$LphpM = array("", "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember");
	$LphpMonatName = htmlentities($LphpM[date("n")]);

	// Tag als Zahl: (z.B.: kurz = "4" / lang = "04")
	$LphpTagKurz = date("j");
	$LphpTagLang = date("d");

	// Monat als Zahl: (z.B.: kurz = "2" / lang = "02")
	$LphpMonatKurz = date("n");
	$LphpMonatLang = date("m");

	// Jahr als Zahl: (z.B.: kurz = "08" / lang = "2008")
	$LphpJahrKurz = date("y");
	$LphpJahrLang = date("Y");

	// Bibeltext ggf. Fett:
	if($LphpBibeltextFett==1){
		$Lphp[1] = "<strong>" . $Lphp[1] . "</strong>";
		$Lphp[5] = "<strong>" . $Lphp[5] . "</strong>";
	}

	// Stellenangabe ggf. als Link zur Internetbibel
	if($LphpBibelLink==1){
		$Lphp[2] = "<a title='Zum Bibeltext' href='" . $Lphp[3] . "' target='_blank'>" . $Lphp[2] . "</a>";
		$Lphp[6] = "<a title='Zum Bibeltext' href='" . $Lphp[7] . "' target='_blank'>" . $Lphp[6] . "</a>";
	}

	// Überschrift zusammenstellen:
	$LphpTitel = "";
	if($LphpTitelText != ""){
		$LphpTitel = htmlentities(trim($LphpTitelText));
	}

	// Datum zusammenstellen:
	$LphpDatum = "";
	if($LphpTitelDatum <1 or $LphpTitelDatum >3){
		$LphpDatum = "";
	}elseif($LphpTitelDatum==1){
		$LphpDatum = $LphpTagLang . "." . $LphpMonatLang . "." . $LphpJahrLang;
	}elseif($LphpTitelDatum==2){
		$LphpDatum = $LphpWochentagName  . ", " . $LphpTagKurz . ". " . $LphpMonatName . " " . $LphpJahrLang;
	}elseif($LphpTitelDatum==3){
		$LphpDatum = $LphpTagKurz . ". " . $LphpMonatName . " " . $LphpJahrLang;
	}

	if($LphpTitel != "" and $LphpDatum != ""){
		$LphpTitel = $LphpTitel . " ";
	}
	$LphpTitel = $LphpTitel . $LphpDatum;
	if($LphpTitel != "" and $LphpTitelDoppelpunkt==1){
		$LphpTitel=$LphpTitel . ":";
	}

	$html .= '<h2 class="plugin-label">'.$LphpTitel.'</h2>';
	$html .= '<br><div class="plugin-content">';

	// Losung ausgeben:
	$html .= $Lphp[0] . $Lphp[1] . "<br>"; 
	$html .= $Lphp[2] . "<br><br>";

	// Lehrtext ausgeben:
	$html .= $Lphp[4] . $Lphp[5] . "<br>";
	$html .= $Lphp[6];
} else {
    $html .= "<b>Losungsdatei ()" . $LphpDatei . " nicht gefunden</b>";
}
$html .= "<br><br><a style='font-size:small' href='https://www.ebu.de' target='Herrnhuter Brüdergemeine'>© Evangelische Brüder-Unität - Herrnhuter Brüdergemeine</a>";
$html .= "<br><a style='font-size:small' href='https://www.losungen.de' target='Losungen'>Weitere Informationen finden Sie hier</a>";
$html .= '</div>';
$html .= '</div>';

return $html;
}}