<?

	class Feed extends SimpleXMLElement implements JsonSerializable{
		public function jsonSerialize(){
			$array = array();
			if ($attributes = $this->attributes()) {
				$array['@attributes'] = iterator_to_array($attributes);
			}
			$namespaces = [null] + $this->getDocNamespaces(true);
			foreach ($namespaces as $namespace) {
				foreach ($this->children($namespace) as $name => $element) {
					if (isset($array[$name])) {
						if (!is_array($array[$name])) {
							$array[$name] = [$array[$name]];
						}
						$array[$name][] = $element;
					} else {
						$array[$name] = $element;
					}
				}
			}
			$text = trim($this);
			if (strlen($text)) {
				if ($array) {
					$array['@text'] = $text;
				} else {
					$array = $text;
				}
			}
			if (!$array) {
				$array = NULL;
			}
			return $array;
		}
		public function toArray() {
			return (array) json_decode(json_encode($this));
		}
	}
	
	function sub2lang($subcode){
		$subname = '';
		if( $subcode == 'en - us' ) $subname = 'English (US)';
		if( $subcode == 'es - la' ) $subname = 'Spanish (Latin American)';
		if( $subcode == 'es - es' ) $subname = 'Spanish';
		if( $subcode == 'fr - fr' ) $subname = 'French';
		if( $subcode == 'pt - br' ) $subname = 'Portuguese (Brazilian)';
		if( $subcode == 'ar - me' ) $subname = 'Arabic';
		if( $subcode == 'it - it' ) $subname = 'Italian';
		if( $subcode == 'de - de' ) $subname = 'German';
		if( $subname == '' ) $subname = $subcode;
		return $subname;
	}
	
	function sub2flag($subcode){
		$flag_uri   = 'http://static.ak.crunchyroll.com/i/country_flags/'; $subname = '';
		if( $subcode == 'en - us' ) $subname = '<img class="flag" src="'.$flag_uri.'us.gif" title="English (US)"/>';
		if( $subcode == 'es - la' ) $subname = '<img class="flag" src="'.$flag_uri.'mx.gif" title="Spanish (Latin American)"/>';
		if( $subcode == 'es - es' ) $subname = '<img class="flag" src="'.$flag_uri.'es.gif" title="Spanish"/>';
		if( $subcode == 'fr - fr' ) $subname = '<img class="flag" src="'.$flag_uri.'fr.gif" title="French"/>';
		if( $subcode == 'pt - br' ) $subname = '<img class="flag" src="'.$flag_uri.'br.gif" title="Portuguese (Brazilian)"/>';
		if( $subcode == 'ar - me' ) $subname = '<img class="flag" src="'.$flag_uri.'sa.gif" title="Arabic"/>';
		if( $subcode == 'it - it' ) $subname = '<img class="flag" src="'.$flag_uri.'it.gif" title="Italian"/>';
		if( $subcode == 'de - de' ) $subname = '<img class="flag" src="'.$flag_uri.'de.gif" title="German"/>';
		if( $subname == '' ) $subname = '[unk]';
		return $subname;
	}
	
	function cc2cn( $code ){
		$country = '';
		if( $code == 'AF' ) $country = 'Afghanistan';
		if( $code == 'AX' ) $country = 'Aland Islands';
		if( $code == 'AL' ) $country = 'Albania';
		if( $code == 'DZ' ) $country = 'Algeria';
		if( $code == 'AS' ) $country = 'American Samoa';
		if( $code == 'AD' ) $country = 'Andorra';
		if( $code == 'AO' ) $country = 'Angola';
		if( $code == 'AI' ) $country = 'Anguilla';
		if( $code == 'AQ' ) $country = 'Antarctica';
		if( $code == 'AG' ) $country = 'Antigua and Barbuda';
		if( $code == 'AR' ) $country = 'Argentina';
		if( $code == 'AM' ) $country = 'Armenia';
		if( $code == 'AW' ) $country = 'Aruba';
		if( $code == 'AU' ) $country = 'Australia';
		if( $code == 'AT' ) $country = 'Austria';
		if( $code == 'AZ' ) $country = 'Azerbaijan';
		if( $code == 'BS' ) $country = 'Bahamas';
		if( $code == 'BH' ) $country = 'Bahrain';
		if( $code == 'BD' ) $country = 'Bangladesh';
		if( $code == 'BB' ) $country = 'Barbados';
		if( $code == 'BY' ) $country = 'Belarus';
		if( $code == 'BE' ) $country = 'Belgium';
		if( $code == 'BZ' ) $country = 'Belize';
		if( $code == 'BJ' ) $country = 'Benin';
		if( $code == 'BM' ) $country = 'Bermuda';
		if( $code == 'BT' ) $country = 'Bhutan';
		if( $code == 'BO' ) $country = 'Bolivia, Plurinational State of';
		if( $code == 'BQ' ) $country = 'Bonaire, Sint Eustatius and Saba';
		if( $code == 'BA' ) $country = 'Bosnia and Herzegovina';
		if( $code == 'BW' ) $country = 'Botswana';
		if( $code == 'BV' ) $country = 'Bouvet Island';
		if( $code == 'BR' ) $country = 'Brazil';
		if( $code == 'IO' ) $country = 'British Indian Ocean Territory';
		if( $code == 'BN' ) $country = 'Brunei Darussalam';
		if( $code == 'BG' ) $country = 'Bulgaria';
		if( $code == 'BF' ) $country = 'Burkina Faso';
		if( $code == 'BI' ) $country = 'Burundi';
		if( $code == 'KH' ) $country = 'Cambodia';
		if( $code == 'CM' ) $country = 'Cameroon';
		if( $code == 'CA' ) $country = 'Canada';
		if( $code == 'CV' ) $country = 'Cape Verde';
		if( $code == 'KY' ) $country = 'Cayman Islands';
		if( $code == 'CF' ) $country = 'Central African Republic';
		if( $code == 'TD' ) $country = 'Chad';
		if( $code == 'CL' ) $country = 'Chile';
		if( $code == 'CN' ) $country = 'China';
		if( $code == 'CX' ) $country = 'Christmas Island';
		if( $code == 'CC' ) $country = 'Cocos (Keeling) Islands';
		if( $code == 'CO' ) $country = 'Colombia';
		if( $code == 'KM' ) $country = 'Comoros';
		if( $code == 'CG' ) $country = 'Congo';
		if( $code == 'CD' ) $country = 'Congo, the Democratic Republic of the';
		if( $code == 'CK' ) $country = 'Cook Islands';
		if( $code == 'CR' ) $country = 'Costa Rica';
		if( $code == 'CI' ) $country = 'Cote d\'Ivoire';
		if( $code == 'HR' ) $country = 'Croatia';
		if( $code == 'CU' ) $country = 'Cuba';
		if( $code == 'CW' ) $country = 'Cyprus';
		if( $code == 'CY' ) $country = 'Cyprus';
		if( $code == 'CZ' ) $country = 'Czech Republic';
		if( $code == 'DK' ) $country = 'Denmark';
		if( $code == 'DJ' ) $country = 'Djibouti';
		if( $code == 'DM' ) $country = 'Dominica';
		if( $code == 'DO' ) $country = 'Dominican Republic';
		if( $code == 'EC' ) $country = 'Ecuador';
		if( $code == 'EG' ) $country = 'Egypt';
		if( $code == 'SV' ) $country = 'El Salvador';
		if( $code == 'GQ' ) $country = 'Equatorial Guinea';
		if( $code == 'ER' ) $country = 'Eritrea';
		if( $code == 'EE' ) $country = 'Estonia';
		if( $code == 'ET' ) $country = 'Ethiopia';
		if( $code == 'FK' ) $country = 'Falkland Islands (Malvinas)';
		if( $code == 'FO' ) $country = 'Faroe Islands';
		if( $code == 'FJ' ) $country = 'Fiji';
		if( $code == 'FI' ) $country = 'Finland';
		if( $code == 'FR' ) $country = 'France';
		if( $code == 'GF' ) $country = 'French Guiana';
		if( $code == 'PF' ) $country = 'French Polynesia';
		if( $code == 'TF' ) $country = 'French Southern Territories';
		if( $code == 'GA' ) $country = 'Gabon';
		if( $code == 'GM' ) $country = 'Gambia';
		if( $code == 'GE' ) $country = 'Georgia';
		if( $code == 'DE' ) $country = 'Germany';
		if( $code == 'GH' ) $country = 'Ghana';
		if( $code == 'GI' ) $country = 'Gibraltar';
		if( $code == 'GR' ) $country = 'Greece';
		if( $code == 'GL' ) $country = 'Greenland';
		if( $code == 'GD' ) $country = 'Grenada';
		if( $code == 'GP' ) $country = 'Guadeloupe';
		if( $code == 'GU' ) $country = 'Guam';
		if( $code == 'GT' ) $country = 'Guatemala';
		if( $code == 'GG' ) $country = 'Guernsey';
		if( $code == 'GN' ) $country = 'Guinea';
		if( $code == 'GW' ) $country = 'Guinea-Bissau';
		if( $code == 'GY' ) $country = 'Guyana';
		if( $code == 'HT' ) $country = 'Haiti';
		if( $code == 'HM' ) $country = 'Heard Island and McDonald Islands';
		if( $code == 'VA' ) $country = 'Holy See (Vatican City State)';
		if( $code == 'HN' ) $country = 'Honduras';
		if( $code == 'HK' ) $country = 'Hong Kong';
		if( $code == 'HU' ) $country = 'Hungary';
		if( $code == 'IS' ) $country = 'Iceland';
		if( $code == 'IN' ) $country = 'India';
		if( $code == 'ID' ) $country = 'Indonesia';
		if( $code == 'IR' ) $country = 'Iran, Islamic Republic of';
		if( $code == 'IQ' ) $country = 'Iraq';
		if( $code == 'IE' ) $country = 'Ireland';
		if( $code == 'IM' ) $country = 'Isle of Man';
		if( $code == 'IL' ) $country = 'Israel';
		if( $code == 'IT' ) $country = 'Italy';
		if( $code == 'JM' ) $country = 'Jamaica';
		if( $code == 'JP' ) $country = 'Japan';
		if( $code == 'JE' ) $country = 'Jersey';
		if( $code == 'JO' ) $country = 'Jordan';
		if( $code == 'KZ' ) $country = 'Kazakhstan';
		if( $code == 'KE' ) $country = 'Kenya';
		if( $code == 'KI' ) $country = 'Kiribati';
		if( $code == 'KP' ) $country = 'Korea, Democratic People\'s Republic of';
		if( $code == 'KR' ) $country = 'Korea, Republic of';
		if( $code == 'KW' ) $country = 'Kuwait';
		if( $code == 'KG' ) $country = 'Kyrgyzstan';
		if( $code == 'LA' ) $country = 'Lao People\'s Democratic Republic';
		if( $code == 'LV' ) $country = 'Latvia';
		if( $code == 'LB' ) $country = 'Lebanon';
		if( $code == 'LS' ) $country = 'Lesotho';
		if( $code == 'LR' ) $country = 'Liberia';
		if( $code == 'LY' ) $country = 'Libya';
		if( $code == 'LI' ) $country = 'Liechtenstein';
		if( $code == 'LT' ) $country = 'Lithuania';
		if( $code == 'LU' ) $country = 'Luxembourg';
		if( $code == 'MO' ) $country = 'Macao';
		if( $code == 'MK' ) $country = 'Macedonia, the former Yugoslav Republic of';
		if( $code == 'MG' ) $country = 'Madagascar';
		if( $code == 'MW' ) $country = 'Malawi';
		if( $code == 'MY' ) $country = 'Malaysia';
		if( $code == 'MV' ) $country = 'Maldives';
		if( $code == 'ML' ) $country = 'Mali';
		if( $code == 'MT' ) $country = 'Malta';
		if( $code == 'MH' ) $country = 'Marshall Islands';
		if( $code == 'MQ' ) $country = 'Martinique';
		if( $code == 'MR' ) $country = 'Mauritania';
		if( $code == 'MU' ) $country = 'Mauritius';
		if( $code == 'YT' ) $country = 'Mayotte';
		if( $code == 'MX' ) $country = 'Mexico';
		if( $code == 'FM' ) $country = 'Micronesia, Federated States of';
		if( $code == 'MD' ) $country = 'Moldova, Republic of';
		if( $code == 'MC' ) $country = 'Monaco';
		if( $code == 'MN' ) $country = 'Mongolia';
		if( $code == 'ME' ) $country = 'Montenegro';
		if( $code == 'MS' ) $country = 'Montserrat';
		if( $code == 'MA' ) $country = 'Morocco';
		if( $code == 'MZ' ) $country = 'Mozambique';
		if( $code == 'MM' ) $country = 'Myanmar';
		if( $code == 'NA' ) $country = 'Namibia';
		if( $code == 'NR' ) $country = 'Nauru';
		if( $code == 'NP' ) $country = 'Nepal';
		if( $code == 'NL' ) $country = 'Netherlands';
		if( $code == 'AN' ) $country = 'Netherlands Antilles';
		if( $code == 'NC' ) $country = 'New Caledonia';
		if( $code == 'NZ' ) $country = 'New Zealand';
		if( $code == 'NI' ) $country = 'Nicaragua';
		if( $code == 'NE' ) $country = 'Niger';
		if( $code == 'NG' ) $country = 'Nigeria';
		if( $code == 'NU' ) $country = 'Niue';
		if( $code == 'NF' ) $country = 'Norfolk Island';
		if( $code == 'MP' ) $country = 'Northern Mariana Islands';
		if( $code == 'NO' ) $country = 'Norway';
		if( $code == 'OM' ) $country = 'Oman';
		if( $code == 'PK' ) $country = 'Pakistan';
		if( $code == 'PW' ) $country = 'Palau';
		if( $code == 'PS' ) $country = 'Palestine, State of';
		if( $code == 'PA' ) $country = 'Panama';
		if( $code == 'PG' ) $country = 'Papua New Guinea';
		if( $code == 'PY' ) $country = 'Paraguay';
		if( $code == 'PE' ) $country = 'Peru';
		if( $code == 'PH' ) $country = 'Philippines';
		if( $code == 'PN' ) $country = 'Pitcairn';
		if( $code == 'PL' ) $country = 'Poland';
		if( $code == 'PT' ) $country = 'Portugal';
		if( $code == 'PR' ) $country = 'Puerto Rico';
		if( $code == 'QA' ) $country = 'Qatar';
		if( $code == 'RE' ) $country = 'Reunion';
		if( $code == 'RO' ) $country = 'Romania';
		if( $code == 'RU' ) $country = 'Russian Federation';
		if( $code == 'RW' ) $country = 'Rwanda';
		if( $code == 'BL' ) $country = 'Saint Barthelemy';
		if( $code == 'SH' ) $country = 'Saint Helena, Ascension and Tristan da Cunha';
		if( $code == 'KN' ) $country = 'Saint Kitts and Nevis';
		if( $code == 'LC' ) $country = 'Saint Lucia';
		if( $code == 'MF' ) $country = 'Saint Martin (French part)';
		if( $code == 'PM' ) $country = 'Saint Pierre and Miquelon';
		if( $code == 'VC' ) $country = 'Saint Vincent and the Grenadines';
		if( $code == 'WS' ) $country = 'Samoa';
		if( $code == 'SM' ) $country = 'San Marino';
		if( $code == 'ST' ) $country = 'Sao Tome and Principe';
		if( $code == 'SA' ) $country = 'Saudi Arabia';
		if( $code == 'SN' ) $country = 'Senegal';
		if( $code == 'RS' ) $country = 'Serbia';
		if( $code == 'SC' ) $country = 'Seychelles';
		if( $code == 'SL' ) $country = 'Sierra Leone';
		if( $code == 'SG' ) $country = 'Singapore';
		if( $code == 'SX' ) $country = 'Sint Maarten (Dutch part)';
		if( $code == 'SK' ) $country = 'Slovakia';
		if( $code == 'SI' ) $country = 'Slovenia';
		if( $code == 'SB' ) $country = 'Solomon Islands';
		if( $code == 'SO' ) $country = 'Somalia';
		if( $code == 'ZA' ) $country = 'South Africa';
		if( $code == 'GS' ) $country = 'South Georgia and the South Sandwich Islands';
		if( $code == 'SS' ) $country = 'South Sudan';
		if( $code == 'ES' ) $country = 'Spain';
		if( $code == 'LK' ) $country = 'Sri Lanka';
		if( $code == 'SD' ) $country = 'Sudan';
		if( $code == 'SR' ) $country = 'Suriname';
		if( $code == 'SJ' ) $country = 'Svalbard and Jan Mayen';
		if( $code == 'SZ' ) $country = 'Swaziland';
		if( $code == 'SE' ) $country = 'Sweden';
		if( $code == 'CH' ) $country = 'Switzerland';
		if( $code == 'SY' ) $country = 'Syrian Arab Republic';
		if( $code == 'TW' ) $country = 'Taiwan, Province of China';
		if( $code == 'TJ' ) $country = 'Tajikistan';
		if( $code == 'TZ' ) $country = 'Tanzania, United Republic of';
		if( $code == 'TH' ) $country = 'Thailand';
		if( $code == 'TL' ) $country = 'Timor-Leste';
		if( $code == 'TG' ) $country = 'Togo';
		if( $code == 'TK' ) $country = 'Tokelau';
		if( $code == 'TO' ) $country = 'Tonga';
		if( $code == 'TT' ) $country = 'Trinidad and Tobago';
		if( $code == 'TN' ) $country = 'Tunisia';
		if( $code == 'TR' ) $country = 'Turkey';
		if( $code == 'TM' ) $country = 'Turkmenistan';
		if( $code == 'TC' ) $country = 'Turks and Caicos Islands';
		if( $code == 'TV' ) $country = 'Tuvalu';
		if( $code == 'UG' ) $country = 'Uganda';
		if( $code == 'UA' ) $country = 'Ukraine';
		if( $code == 'AE' ) $country = 'United Arab Emirates';
		if( $code == 'GB' ) $country = 'United Kingdom';
		if( $code == 'US' ) $country = 'United States';
		if( $code == 'UM' ) $country = 'United States Minor Outlying Islands';
		if( $code == 'UY' ) $country = 'Uruguay';
		if( $code == 'UZ' ) $country = 'Uzbekistan';
		if( $code == 'VU' ) $country = 'Vanuatu';
		if( $code == 'VE' ) $country = 'Venezuela, Bolivarian Republic of';
		if( $code == 'VN' ) $country = 'Vietnam';
		if( $code == 'VG' ) $country = 'Virgin Islands, British';
		if( $code == 'VI' ) $country = 'Virgin Islands, U.S.';
		if( $code == 'WF' ) $country = 'Wallis and Futuna';
		if( $code == 'EH' ) $country = 'Western Sahara';
		if( $code == 'YE' ) $country = 'Yemen';
		if( $code == 'ZM' ) $country = 'Zambia';
		if( $code == 'ZW' ) $country = 'Zimbabwe';
		if( $country == '') $country = $code;
		return $country;
	}
	

?>