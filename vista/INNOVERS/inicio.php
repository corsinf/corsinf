<?php

if ($_GET['acc'] == 'in_personas') {
	include('INNOVERS/PERSONAS/in_personas.php');
}

if ($_GET['acc'] == 'in_registrar_personas') {
	include('INNOVERS/PERSONAS/in_registrar_personas.php');
}

if ($_GET['acc'] == 'in_registrar_student_consent') {
	include('INNOVERS/STUDENT_CONSENT/in_registrar_student_consent.php');
}

if ($_GET['acc'] == 'in_pdf_stundent_consent') {
	include('INNOVERS/STUDENT_CONSENT/in_pdf_stundent_consent.php');
}

