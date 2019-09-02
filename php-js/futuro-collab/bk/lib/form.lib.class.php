<?php

class libForm
{
	// Validira forme ako postoje $_POST polja i nisu prazna  tj. vrijednost im nije ''
	public static function validatePostForm( $fields, $required = array() )
	{
		$valid = true;
		
		// Validacija postojanja polja tj. da je sama forma validna.
		foreach( $fields as $field )
		{
			if( ! isset( $_POST[ $field ] ))
			{
				$valid = false;
			}
		}
		
		// Validacija jesu li važna polja prazna ili ne.
		foreach( $required as $field )
		{
			if( ! isset( $_POST[ $field ] ) || $_POST[ $field ] === '' )
			{
				$valid = false;
				
				// Ako postoji ime polja za error pod form- sectionom unutar $txt varijable.
				if( libTemplate::txt( 'form-' . $field ) )
				{
					$fieldName = libTemplate::txt( 'form-' . $field );
				}
				
				// Ako postoji ime polja zasebno. Tipa client.
				else if( libTemplate::txt( $field ) )
				{
					$fieldName = libTemplate::txt( $field );
				}
				else
				{
					$fieldName = '';
				}
				
				libTemplate::addError( 'Niste popunili važni podatak: ' . $fieldName ); // Trebalo bi rješiti prevodivost polja - username / korisničko ime i tako to.
			}
		}
		
		return $valid;
	}
}