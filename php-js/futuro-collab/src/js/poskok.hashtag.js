/*
 * Stvaranje eventa koji se izvršava na promjenu hashtaga
 * Funkcije vezane za hashtags.
 */
 
// globalna varijabla koja pamti zadnji hash
var PreviousHashTag = location.hash;
 
// globalna varijabla koja pamti zadnji mijenjani hash property.
var PreviousHashProperty = '';

//provjera da li se hash tag promjenio
function CheckHash()
{
	if( PreviousHashTag != location.hash )
	{
		// pozivamo custom event
		$('body').trigger("hashchange", [ getHashObj() ]);
		PreviousHashTag = location.hash;
		setTimeout( "CheckHash()", 200);
	}
	setTimeout( "CheckHash()", 100);
}

// Dovhvati informacije o hash-u.
function getHash()
{
	if( location.hash == '' )
	{
		return '';
	}
	else
	{
		return location.hash.substr(1);
	}
};

// Dohvati hash objekt.
function getHashObj()
{
    var hashValue = getHash();
    var hashObj = {};
    
    if(hashValue.length > 0)
    {
        var vars = hashValue.split(';');
        
        for( var i = 0; i < vars.length; i++ )
        {
			if( vars[i] !== '' )
			{
				var itemInfo = vars[i].split('=');
				
				hashObj[ itemInfo[0] ] = itemInfo[1];
			}
        }
    }

    return hashObj;
}

// Dohvati atribut hash objekta.
function getHashTag( property )
{
	return getHashObj()[ property ];
}

function hasHash( string )
{
	return string.indexOf('#') !== -1 ? true : false;
}

// Postavi hash atribut.
function setHash( property, value )
{
	PreviousHashProperty = property;
	
	var hashObj = getHashObj();
		hashObj[property] = value;
	
	var newHash = '';
	
	for( property in hashObj )
	{
		if( hashObj[property] !== '' )
		{
			newHash += property + '=' + hashObj[property] + ';';
		}
	}
	
	location.hash = newHash;
}

$(document).ready(function()
{
	// izvršavamo provjeru svakih 100 milisekundu
	setTimeout('CheckHash()', 100);
});