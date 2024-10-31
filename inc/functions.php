<?php
function bbpsd_create_basic_structure() {
	$userids = array();

	// Create 1 user with accents
	$user_id = bbpsd_create_unique_person( true );
	if ( !$user_id ) {
		return false;
	}
	$userids[] = $user_id;
	// Create 6 other users
	$i = 0;
	$err = 0;
	do {
		$user_id = bbpsd_create_unique_person();
		if ( $user_id ) {
			$i++;
			$userids[] = $user_id;
		} else {
			$err++;
		}
		if ( $err == 6 ) return false;
	} while ( $i<6 );
	echo 'Created 6 users<br>';

	// Create top forum category
	$forum_data = array(
		'post_parent'    => 0,
		'post_content'   => 'The top category for the menu',
		'post_title'     => 'Menu',
	);
	$forum_meta_data = array(
		'_bbp_forum_type'    => 'category',
	);
	if(function_exists('bbp_insert_forum')) {
		$forum_root_id = bbp_insert_forum( $forum_data, $forum_meta_data );
		echo 'Created Forum category ' .  $forum_data['post_title'] . '<br>';
	} else {
		return false;
	}

	// Create 2 subforum category
	$forum_meta_data = array(
		'_bbp_forum_type'    => 'category',
	);
	$forum_data = array(
		'post_parent'    => $forum_root_id,
		'post_content'   => 'The category for the food',
		'post_title'     => 'Food',
	);
	$forum_11_id = bbp_insert_forum( $forum_data, $forum_meta_data );
	echo 'Created Forum category ' .  $forum_data['post_title'] . '<br>';
	$forum_data = array(
		'post_parent'    => $forum_root_id,
		'post_content'   => 'The category for the drinks',
		'post_title'     => 'Beverages',
	);
	$forum_12_id = bbp_insert_forum( $forum_data, $forum_meta_data );	
	echo 'Created Forum category ' .  $forum_data['post_title'] . '<br>';

	// Create 4 subforums
	$forumids = array();
	$forum_data = array(
		'post_parent'    => $forum_11_id,
		'post_content'   => 'All you need to know about pasta.',
		'post_title'     => 'Pasta',
	);
	$forumids[111] = bbp_insert_forum( $forum_data );
	echo 'Created Forum ' .  $forum_data['post_title'] . '<br>';
	$forum_data = array(
		'post_parent'    => $forum_11_id,
		'post_content'   => 'This is the place to be for steaks.',
		'post_title'     => 'Steaks',
	);
	$forumids[112] = bbp_insert_forum( $forum_data );
	echo 'Created Forum ' .  $forum_data['post_title'] . '<br>';
	$forum_data = array(
		'post_parent'    => $forum_11_id,
		'post_content'   => 'This forum is intentionally left empty.',
		'post_title'     => 'Empty',
	);
	$forum_113_id = bbp_insert_forum( $forum_data );
	echo 'Created Forum ' .  $forum_data['post_title'] . '<br>';
	$forum_data = array(
		'post_parent'    => $forum_12_id,
		'post_content'   => 'One place. One whisky. The sweet smell of success.',
		'post_title'     => 'Whiskey',
	);
	$forumids[121] = bbp_insert_forum( $forum_data );
	echo 'Created Forum ' .  $forum_data['post_title'] . '<br>';
	$forum_data = array(
		'post_parent'    => $forum_12_id,
		'post_content'   => 'How to squeeze the most out of juicing.',
		'post_title'     => 'Juices',
	);
	$forumids[122] = bbp_insert_forum( $forum_data );
	echo 'Created Forum ' .  $forum_data['post_title'] . '<br>';

	// Create 4 topics in all forums (except the empty one)
	$topicids = array();
	for ($i = 0; $i <= 3; $i++) {
		foreach ( $forumids as $forum_id ) {
			$randIndex = array_rand( $userids );
			$topic_data = array(
				'post_parent'  => $forum_id,
				'post_content' => bbpsd_get_cupcake_quote( 3 ),
				'post_title'   => bbpsd_get_cupcake_quote( 1 ),
				'post_author'  => $userids[$randIndex],
				);
			$topic_id = bbp_insert_topic( $topic_data );
			if ( $topic_id ) {
				$topicids[] = $topic_id;
			} else {
				return false;
			}
		}
	}
	echo 'Created Topics<br>';
	
	// Create 2 replies in all topics
	for ($i = 0; $i < 2; $i++) {
		foreach ( $topicids as $topic_id ) {
			$randIndex = array_rand( $userids );
			$reply_data = array(
				'post_parent'  => $topic_id,
				'post_content' => bbpsd_get_cupcake_quote( 3 ),
				'post_title'   => bbpsd_get_cupcake_quote( 1 ),
				'post_author'  => $userids[$randIndex],
				);
			$reply_id = bbp_insert_reply( $reply_data );
			if ( !$reply_id ) {
				return false;
			}
		}
	}
	echo 'Created Replies<br>';

	// Create 2 anonymous topics with anonymous replies
	for ($i = 0; $i < 2; $i++) {
		$anon_meta_data = array(
			'_bbp_anonymous_email' => 'anon@example.com',
			'_bbp_anonymous_name'  => 'A NonyMous',
		);
		$topic_data = array(
			'post_parent'  => $forumids[111],
			'post_content' => bbpsd_get_cupcake_quote( 3 ),
			'post_title'   => 'An anonymous topic ' . bbpsd_get_cupcake_quote( 1 ),
			'post_author'  => 0,
			);
		$topic_id = bbp_insert_topic( $topic_data, $anon_meta_data );
		if ( $topic_id ) {
			$reply_data = array(
				'post_parent'  => $topic_id,
				'post_content' => bbpsd_get_cupcake_quote( 3 ),
				'post_title'   => bbpsd_get_cupcake_quote( 1 ),
				'post_author'  => 0,
				);
			$reply_id = bbp_insert_reply( $reply_data, $anon_meta_data );
			if ( !$reply_id ) {
				return false;
			}
		} else {
			return false;
		}
	}	
	echo 'Created Anonymous Topics and Replies<br>';

	
	if(function_exists('bbp_admin_repair_topic_meta')) {
		bbp_admin_repair_topic_meta();
	}
	if(function_exists('bbp_admin_repair_forum_meta')) {
		bbp_admin_repair_forum_meta();
	}
	if(function_exists('bbp_admin_repair_forum_visibility')) {
		bbp_admin_repair_forum_visibility();
	}
	if(function_exists('bbp_admin_repair_freshness')) {
		bbp_admin_repair_freshness();
	}
	if(function_exists('bbp_admin_repair_sticky')) {
		bbp_admin_repair_sticky();
	}
	if(function_exists('bbp_admin_repair_forum_topic_count')) {
		bbp_admin_repair_forum_topic_count();
	}
	if(function_exists('bbp_admin_repair_forum_reply_count')) {
		bbp_admin_repair_forum_reply_count();
	}
	if(function_exists('bbp_admin_repair_topic_reply_count')) {
		bbp_admin_repair_topic_reply_count();
	}
	if(function_exists('bbp_admin_repair_topic_voice_count')) {
		bbp_admin_repair_topic_voice_count();
	}
	if(function_exists('bbp_admin_repair_topic_hidden_reply_count')) {
		bbp_admin_repair_topic_hidden_reply_count();
	}
	if(function_exists('bbp_admin_repair_user_topic_count')) {
		bbp_admin_repair_user_topic_count();
	}
	if(function_exists('bbp_admin_repair_user_reply_count')) {
		bbp_admin_repair_user_reply_count();
	}
	if(function_exists('bbp_admin_repair_user_roles')) {
		bbp_admin_repair_user_roles();
	}

	// assign 2 global moderators
	$randIndex = array_rand($userids);
	$ret = bbp_set_user_role( $userids[$randIndex], 'bbp_moderator' );
	$randIndex2 = array_rand($userids);
	while ( $randIndex == $randIndex2 ) {
		$randIndex2 = array_rand($userids);
	}
	bbp_set_user_role( $userids[$randIndex2], 'bbp_moderator' );
	echo 'Created Moderators<br>';


	
	return true;
}

function bbpsd_create_unique_person( $accented = false ) {
	$firstName = array(
        'Aaron', 'Adam', 'Adrian', 'Aiden', 'Alan', 'Alex', 'Alexander', 'Alfie', 'Andrew', 'Andy', 'Anthony', 'Archie', 'Arthur', 'Barry', 'Ben', 'Benjamin', 'Bradley', 'Brandon', 'Bruce', 'Callum', 'Cameron', 'Charles', 'Charlie', 'Chris', 'Christian', 'Christopher', 'Colin', 'Connor', 'Craig', 'Dale', 'Damien', 'Dan', 'Daniel', 'Darren', 'Dave', 'David', 'Dean', 'Dennis', 'Dominic', 'Duncan', 'Dylan', 'Edward', 'Elliot', 'Elliott', 'Ethan', 'Finley', 'Frank', 'Fred', 'Freddie', 'Gary', 'Gavin', 'George', 'Gordon', 'Graham', 'Grant', 'Greg', 'Harley', 'Harrison', 'Harry', 'Harvey', 'Henry', 'Ian', 'Isaac', 'Jack', 'Jackson', 'Jacob', 'Jake', 'James', 'Jamie', 'Jason', 'Jayden', 'Jeremy', 'Jim', 'Joe', 'Joel', 'John', 'Jonathan', 'Jordan', 'Joseph', 'Joshua', 'Karl', 'Keith', 'Ken', 'Kevin', 'Kieran', 'Kyle', 'Lee', 'Leo', 'Lewis', 'Liam', 'Logan', 'Louis', 'Lucas', 'Luke', 'Mark', 'Martin', 'Mason', 'Matthew', 'Max', 'Michael', 'Mike', 'Mohammed', 'Muhammad', 'Nathan', 'Neil', 'Nick', 'Noah', 'Oliver', 'Oscar', 'Owen', 'Patrick', 'Paul', 'Pete', 'Peter', 'Philip', 'Quentin', 'Ray', 'Reece', 'Riley', 'Rob', 'Ross', 'Ryan', 'Samuel', 'Scott', 'Sean', 'Sebastian', 'Stefan', 'Stephen', 'Steve', 'Theo', 'Thomas', 'Tim', 'Toby', 'Tom', 'Tony', 'Tyler', 'Wayne', 'Will', 'William', 'Zachary', 'Zach', 'Abbie', 'Abigail', 'Adele', 'Alexa', 'Alexandra', 'Alice', 'Alison', 'Amanda', 'Amber', 'Amelia', 'Amy', 'Anna', 'Ashley', 'Ava', 'Beth', 'Bethany', 'Becky', 'Caitlin', 'Candice', 'Carlie', 'Carmen', 'Carole', 'Caroline', 'Carrie', 'Charlotte', 'Chelsea', 'Chloe', 'Claire', 'Courtney', 'Daisy', 'Danielle', 'Donna', 'Eden', 'Eileen', 'Eleanor', 'Elizabeth', 'Ella', 'Ellie', 'Elsie', 'Emily', 'Emma', 'Erin', 'Eva', 'Evelyn', 'Evie', 'Faye', 'Fiona', 'Florence', 'Francesca', 'Freya', 'Georgia', 'Grace', 'Hannah', 'Heather', 'Helen', 'Helena', 'Hollie', 'Holly', 'Imogen', 'Isabel', 'Isabella', 'Isabelle', 'Isla', 'Isobel', 'Jade', 'Jane', 'Jasmine', 'Jennifer', 'Jessica', 'Jodie', 'Julia', 'Julie', 'Justine', 'Karen', 'Karlie', 'Katie', 'Keeley', 'Kelly', 'Kimberly', 'Kirsten', 'Kirsty', 'Laura', 'Lauren', 'Layla', 'Leah', 'Leanne', 'Lexi', 'Lilly', 'Lily', 'Linda', 'Lindsay', 'Lisa', 'Lizzie', 'Lola', 'Lucy', 'Maisie', 'Mandy', 'Maria', 'Mary', 'Matilda', 'Megan', 'Melissa', 'Mia', 'Millie', 'Molly', 'Naomi', 'Natalie', 'Natasha', 'Nicole', 'Nikki', 'Olivia', 'Patricia', 'Paula', 'Pauline', 'Phoebe', 'Poppy', 'Rachel', 'Rebecca', 'Rosie', 'Rowena', 'Roxanne', 'Ruby', 'Ruth', 'Sabrina', 'Sally', 'Samantha', 'Sarah', 'Sasha', 'Scarlett', 'Selina', 'Shannon', 'Sienna', 'Sofia', 'Sonia', 'Sophia', 'Sophie', 'Stacey', 'Stephanie','Suzanne', 'Summer', 'Tanya', 'Tara', 'Teagan', 'Theresa', 'Tiffany', 'Tina', 'Tracy', 'Vanessa', 'Vicky', 'Victoria', 'Wendy', 'Yasmine', 'Yvette', 'Yvonne', 'Zoe',
    );
	
	$lastName = array(
        'ADAMS', 'ALLEN', 'ANDERSON', 'BAILEY', 'BAKER', 'BELL', 'BENNETT', 'BROWN', 'BUTLER', 'CAMPBELL', 'CARTER', 'CHAPMAN', 'CLARK', 'CLARKE', 'COLLINS', 'COOK', 'COOPER', 'COX', 'DAVIES', 'DAVIS', 'EDWARDS', 'ELLIS', 'EVANS', 'FOX', 'GRAHAM', 'GRAY', 'GREEN', 'GRIFFITHS', 'HALL', 'HARRIS', 'HARRISON', 'HILL', 'HOLMES', 'HUGHES', 'HUNT', 'HUNTER', 'JACKSON', 'JAMES', 'JOHNSON', 'JONES', 'KELLY', 'KENNEDY', 'KHAN', 'KING', 'KNIGHT', 'LEE', 'LEWIS', 'LLOYD', 'MARSHALL', 'MARTIN', 'MASON', 'MATTHEWS', 'MILLER', 'MITCHELL', 'MOORE', 'MORGAN', 'MORRIS', 'MURPHY', 'MURRAY', 'OWEN', 'PALMER', 'PARKER', 'PATEL', 'PHILLIPS', 'POWELL', 'PRICE', 'REID', 'REYNOLDS', 'RICHARDS', 'RICHARDSON', 'ROBERTS', 'ROBERTSON', 'ROBINSON', 'ROGERS', 'ROSE', 'ROSS', 'RUSSELL', 'SAUNDERS', 'SCOTT', 'SHAW', 'SIMPSON', 'SMITH', 'STEVENS', 'STEWART', 'TAYLOR', 'THOMAS', 'THOMPSON', 'TURNER', 'WALKER', 'WALSH', 'WARD', 'WATSON', 'WHITE', 'WILKINSON', 'WILLIAMS', 'WILSON', 'WOOD', 'WRIGHT', 'YOUNG',
		'ABAD', 'ABEYTA', 'ABREGO', 'ABREU', 'ACEVEDO', 'ACOSTA', 'ACUÑA', 'ADAME', 'ADORNO', 'AGOSTO', 'AGUADO', 'AGUAYO', 'AGUILAR', 'AGUILERA', 'AGUIRRE', 'ALANIS', 'ALANIZ', 'ALARCÓN', 'ALBA', 'ALCALA', 'ALCARÁZ', 'ALCÁNTAR', 'ALEJANDRO', 'ALEMÁN', 'ALFARO', 'ALFONSO', 'ALICEA', 'ALMANZA', 'ALMARÁZ', 'ALMONTE', 'ALONSO', 'ALONZO', 'ALTAMIRANO', 'ALVA', 'ALVARADO', 'ÁLVAREZ', 'AMADOR', 'AMAYA', 'ANAYA', 'ANDREU', 'ANDRÉS', 'ANGUIANO', 'ANGULO', 'ANTÓN', 'APARICIO', 'APODACA', 'APONTE', 'ARAGÓN', 'ARANDA', 'ARAÑA', 'ARCE', 'ARCHULETA', 'ARELLANO', 'ARENAS', 'AREVALO', 'ARGUELLO', 'ARIAS', 'ARMAS', 'ARMENDÁRIZ', 'ARMENTA', 'ARMIJO', 'ARREDONDO', 'ARREOLA', 'ARRIAGA', 'ARRIBAS', 'ARROYO', 'ARTEAGA', 'ASENSIO', 'ATENCIO', 'ÁVALOS', 'ÁVILA', 'AVILÉS', 'AYALA', 'BACA', 'BADILLO', 'BAEZA', 'BAHENA', 'BALDERAS', 'BALLESTEROS', 'BANDA', 'BARAJAS', 'BARELA', 'BARRAGÁN', 'BARRAZA', 'BARRERA', 'BARRETO', 'BARRIENTOS', 'BARRIOS', 'BARROSO', 'BATISTA', 'BAUTISTA', 'BAÑUELOS', 'BECERRA', 'BELTRÁN', 'BENAVIDES', 'BENAVÍDEZ', 'BENITO', 'BENÍTEZ', 'BERMEJO', 'BERMÚDEZ', 'BERNAL', 'BERRÍOS', 'BLANCO', 'BLASCO', 'BLÁZQUEZ', 'BONILLA', 'BORREGO', 'BOTELLO', 'BRAVO', 'BRIONES', 'BRISEÑO', 'BRITO', 'BUENO', 'BURGOS', 'BUSTAMANTE', 'BUSTOS', 'BÁEZ', 'BETANCOURT', 'CABALLERO', 'CABELLO', 'CABRERA', 'CABÁN', 'CADENA', 'CALDERA', 'CALDERÓN', 'CALERO', 'CALVILLO', 'CALVO', 'CAMACHO', 'CAMARILLO', 'CAMPOS', 'CANALES', 'CANDELARIA', 'CANO', 'CANTÚ', 'CARABALLO', 'CARBAJAL', 'CARBALLO', 'CARBONELL', 'CÁRDENAS', 'CARDONA', 'CARMONA', 'CARO', 'CARRANZA', 'CARRASCO', 'CARRASQUILLO', 'CARRERA', 'CARRERO', 'CARRETERO', 'CARREÓN', 'CARRILLO', 'CARRIÓN', 'CARVAJAL', 'CASADO', 'CASANOVA', 'CASARES', 'CASAS', 'CASILLAS', 'CASTAÑEDA', 'CASTAÑO', 'CASTELLANO', 'CASTELLANOS', 'CASTILLO', 'CASTRO', 'CASÁREZ', 'CAVAZOS', 'CAZARES', 'CEBALLOS', 'CEDILLO', 'CEJA', 'CENTENO', 'CEPEDA', 'CERDA', 'CERVANTES', 'CERVÁNTEZ', 'CHACÓN', 'CHAPA', 'CHAVARRÍA', 'CHÁVEZ', 'CINTRÓN', 'CISNEROS', 'CLEMENTE', 'COBO', 'COLLADO', 'COLLAZO', 'COLUNGA', 'COLÓN', 'CONCEPCIÓN', 'CONDE', 'CONTRERAS', 'CORDERO', 'CORNEJO', 'CORONA', 'CORONADO', 'CORRAL', 'CORRALES', 'CORREA', 'CORTÉS', 'CORTEZ', 'CORTÉS', 'COSTA', 'COTTO', 'COVARRUBIAS', 'CRESPO', 'CRUZ', 'CUELLAR', 'CUENCA', 'CUESTA', 'CUEVAS', 'CURIEL', 'CÓRDOBA', 'CÓRDOVA', 'DE ANDA', 'DE JESÚS','DE LA CRUZ', 'DE LA FUENTE', 'DE LA TORRE', 'DEL RÍO', 'DELACRÚZ', 'DELAFUENTE', 'DELAGARZA', 'DELAO', 'DELAPAZ', 'DELAROSA', 'DELATORRE', 'DELEÓN', 'DELGADILLO', 'DELGADO', 'DELRÍO', 'DELVALLE', 'DÍEZ', 'DOMENECH', 'DOMINGO', 'DOMÍNGUEZ', 'DOMÍNQUEZ', 'DUARTE', 'DUEÑAS', 'DURAN', 'DÁVILA', 'DÍAZ', 'ECHEVARRÍA', 'ELIZONDO', 'ENRÍQUEZ', 'ESCALANTE', 'ESCAMILLA', 'ESCOBAR', 'ESCOBEDO', 'ESCRIBANO', 'ESCUDERO', 'ESPARZA', 'ESPINAL', 'ESPINO', 'ESPINOSA', 'ESPINOZA', 'ESQUIBEL', 'ESQUIVEL', 'ESTEBAN', 'ESTEVE', 'ESTRADA', 'ESTÉVEZ', 'EXPÓSITO', 'FAJARDO', 'FARÍAS', 'FELICIANO', 'FERNÁNDEZ', 'FERRER', 'FIERRO', 'FIGUEROA', 'FLORES', 'FLÓREZ', 'FONSECA', 'FONT', 'FRANCO', 'FRÍAS', 'FUENTES', 'GAITÁN', 'GALARZA', 'GALINDO', 'GALLARDO', 'GALLEGO', 'GALLEGOS', 'GALVÁN', 'GALÁN', 'GAMBOA', 'GÁMEZ', 'GAONA', 'GARAY', 'GARCÍA', 'GARIBAY', 'GARICA', 'GARRIDO', 'GARZA', 'GASTÉLUM', 'GAYTÁN', 'GIL', 'GIMENO', 'GIMÉNEZ', 'GIRÓN', 'GODOY', 'GODÍNEZ', 'GONZÁLES', 'GONZÁLEZ', 'GRACIA', 'GRANADO', 'GRANADOS', 'GRIEGO', 'GRIJALVA', 'GUAJARDO', 'GUARDADO', 'GUERRA', 'GUERRERO', 'GUEVARA', 'GUILLEN', 'GURULE', 'GUTIÉRREZ', 'GUZMÁN', 'GÁLVEZ', 'GÓMEZ', 'HARO', 'HENRÍQUEZ', 'HEREDIA', 'HERNÁNDES', 'HERNANDO', 'HERNÁDEZ', 'HERNÁNDEZ', 'HERRERA', 'HERRERO', 'HIDALGO', 'HINOJOSA', 'HOLGUÍN', 'HUERTA', 'HURTADO', 'IBARRA', 'IBÁÑEZ', 'IGLESIAS', 'IRIZARRY', 'IZQUIERDO', 'JAIME', 'JAIMES', 'JARAMILLO', 'JASSO', 'JIMÉNEZ', 'JIMÍNEZ', 'JUAN', 'JURADO', 'JUÁREZ', 'JÁQUEZ', 'LABOY', 'LARA', 'LAUREANO', 'LEAL', 'LEBRÓN', 'LEDESMA', 'LEIVA', 'LEMUS', 'LERMA', 'LEYVA', 'LEÓN', 'LIMÓN', 'LINARES', 'LIRA', 'LLAMAS', 'LLORENTE', 'LOERA', 'LOMELI', 'LONGORIA', 'LORENTE', 'LORENZO', 'LOVATO', 'LOYA', 'LOZADA', 'LOZANO', 'LUCAS', 'LUCERO', 'LUCIO', 'LUEVANO', 'LUGO', 'LUIS', 'LUJÁN', 'LUNA', 'LUQUE', 'LÁZARO', 'LÓPEZ', 'MACIAS', 'MACÍAS', 'MADERA', 'MADRID', 'MADRIGAL', 'MAESTAS', 'MAGAÑA', 'MALAVE', 'MALDONADO', 'MANZANARES', 'MANZANO', 'MARCO', 'MARCOS', 'MARES', 'MARRERO', 'MARROQUÍN', 'MARTOS', 'MARTÍ', 'MARTÍN', 'MARTÍNEZ', 'MARÍN', 'MÁS', 'MASCAREÑAS', 'MATA', 'MATEO', 'MATEOS', 'MATOS', 'MATÍAS', 'MAYA', 'MAYORGA', 'MEDINA', 'MEDRANO', 'MEJÍA', 'MELGAR', 'MELÉNDEZ', 'MENA', 'MENCHACA', 'MENDOZA', 'MENÉNDEZ', 'MERAZ', 'MERCADO', 'MERINO', 'MESA', 'MEZA', 'MIGUEL', 'MILLÁN', 'MIRAMONTES', 'MIRANDA', 'MIRELES', 'MOJICA', 'MOLINA', 'MONDRAGÓN', 'MONROY', 'MONTALVO', 'MONTAÑEZ', 'MONTAÑO', 'MONTEMAYOR', 'MONTENEGRO', 'MONTERO', 'MONTES', 'MONTEZ', 'MONTOYA', 'MORA', 'MORAL', 'MORALES', 'MORÁN', 'MORENO', 'MOTA', 'MOYA', 'MUNGUÍA', 'MURILLO', 'MURO', 'MUÑIZ', 'MUÑOZ', 'MÁRQUEZ', 'MÉNDEZ', 'NARANJO', 'NARVÁEZ', 'NAVA', 'NAVARRETE', 'NAVARRO', 'NAVAS', 'NAZARIO', 'NEGRETE', 'NEGRÓN', 'NEVÁREZ', 'NIETO', 'NIEVES', 'NIÑO', 'NORIEGA', 'NÁJERA', 'NÚÑEZ', 'OCAMPO', 'OCASIO', 'OCHOA', 'OJEDA', 'OLIVA', 'OLIVARES', 'OLIVAS', 'OLIVER', 'OLIVERA', 'OLIVO', 'OLIVÁREZ', 'OLMOS', 'OLVERA', 'ONTIVEROS', 'OQUENDO', 'ORDOÑEZ', 'ORDÓÑEZ', 'ORELLANA', 'ORNELAS', 'OROSCO', 'OROZCO', 'ORTA', 'ORTEGA', 'ORTÍZ', 'OSORIO', 'OTERO', 'OZUNA', 'PABÓN', 'PACHECO', 'PADILLA', 'PADRÓN', 'PAGAN', 'PALACIOS', 'PALOMINO', 'PALOMO', 'PANTOJA', 'PARDO', 'PAREDES', 'PARRA', 'PARTIDA', 'PASCUAL', 'PASTOR', 'PATIÑO', 'PAZ', 'PEDRAZA', 'PEDROZA', 'PELAYO', 'PELÁEZ', 'PERALES', 'PERALTA', 'PEREA', 'PEREIRA', 'PERES', 'PEÑA', 'PICHARDO', 'PINEDA', 'PIZARRO', 'PIÑA', 'PIÑEIRO', 'PLAZA', 'POLANCO', 'POLO', 'PONCE', 'PONS', 'PORRAS', 'PORTILLO', 'POSADA', 'POZO', 'PRADO', 'PRECIADO', 'PRIETO', 'PUENTE', 'PUGA', 'PUIG', 'PULIDO', 'PÁEZ', 'PÉREZ', 'QUESADA', 'QUEZADA', 'QUINTANA', 'QUINTANILLA', 'QUINTERO', 'QUIROZ', 'QUIÑONES', 'QUIÑÓNEZ', 'RAEL', 'RAMOS', 'RAMÍREZ', 'RAMÓN', 'RANGEL', 'RASCÓN', 'RAYA', 'RAZO', 'REDONDO', 'REGALADO', 'REINA', 'RENDÓN', 'RENTERÍA', 'REQUENA', 'RESÉNDEZ', 'REY', 'REYES', 'REYNA', 'REYNOSO', 'RICO', 'RIERA', 'RINCÓN', 'RIOJAS', 'RIVAS', 'RIVERA', 'RIVERO', 'ROBLEDO', 'ROBLES', 'ROCA', 'ROCHA', 'RODARTE', 'RODRIGO', 'RODRÍGUEZ', 'RODRÍQUEZ', 'ROIG', 'ROJAS', 'ROJO', 'ROLDÁN', 'ROLÓN', 'ROMERO', 'ROMO', 'ROMÁN', 'ROQUE', 'ROS', 'ROSA', 'ROSADO', 'ROSALES', 'ROSARIO', 'ROSAS', 'ROYBAL', 'RUBIO', 'RUEDA', 'RUELAS', 'RUIZ', 'RUVALCABA', 'RUÍZ', 'RÍOS', 'SAAVEDRA', 'SAIZ', 'SALAS', 'SALAZAR', 'SALCEDO', 'SALCIDO', 'SALDAÑA', 'SALDIVAR', 'SALGADO', 'SALINAS', 'SALVADOR', 'SAMANIEGO', 'SANABRIA', 'SÁNCHEZ', 'SANCHO', 'SANDOVAL', 'SANTACRUZ', 'SANTAMARÍA', 'SANTANA', 'SANTIAGO', 'SANTILLÁN', 'SANTOS', 'SANZ', 'SARABIA', 'SAUCEDA', 'SAUCEDO', 'SEDILLO', 'SEGOVIA', 'SEGURA', 'SEPÚLVEDA', 'SERNA', 'SERRA', 'SERRANO', 'SERRATO', 'SEVILLA', 'SIERRA', 'SILVA', 'SIMÓN', 'SISNEROS', 'SOLA', 'SOLANO', 'SOLER', 'SOLIZ', 'SOLORIO', 'SOLORZANO', 'SOLÍS', 'SORIA', 'SORIANO', 'SOSA', 'SOTELO', 'SOTO', 'SUÁREZ', 'SÁENZ', 'SÁEZ', 'SÁNCHEZ', 'TAFOYA', 'TAMAYO', 'TAMEZ', 'TAPIA', 'TEJADA', 'TEJEDA', 'TELLO', 'TERRAZAS', 'TERÁN', 'TIJERINA', 'TIRADO', 'TOLEDO', 'TORO', 'TORRES', 'TOVAR', 'TREJO', 'TREVIÑO', 'TRUJILLO', 'TÉLLEZ', 'TÓRREZ', 'ULIBARRI', 'ULLOA', 'URBINA', 'UREÑA', 'URIBE', 'URRUTIA', 'URÍAS', 'VACA', 'VALADEZ', 'VALDEZ', 'VALDIVIA', 'VALDÉS', 'VALENCIA', 'VALENTÍN', 'VALENZUELA', 'VALERO', 'VALLADARES', 'VALLE', 'VALLEJO', 'VALLES', 'VALVERDE', 'VANEGAS', 'VARELA', 'VARGAS', 'VEGA', 'VELA', 'VELASCO', 'VELÁSQUEZ', 'VELÁZQUEZ', 'VENEGAS', 'VERA', 'VERDUGO', 'VERDUZCO', 'VERGARA', 'VICENTE', 'VIDAL', 'VIERA', 'VIGIL', 'VILA', 'VILLA', 'VILLAGÓMEZ', 'VILLALBA', 'VILLALOBOS', 'VILLALPANDO', 'VILLANUEVA', 'VILLAR', 'VILLAREAL', 'VILLARREAL', 'VILLASEÑOR', 'VILLEGAS', 'VÁSQUEZ', 'VÁZQUEZ', 'VÉLEZ', 'VÉLIZ', 'YBARRA', 'YÁÑEZ', 'ZAMBRANO', 'ZAMORA', 'ZAMUDIO', 'ZAPATA', 'ZARAGOZA', 'ZARATE', 'ZAVALA', 'ZAYAS', 'ZELAYA', 'ZEPEDA', 'ZÚÑIGA',
	);
	
	$randIndex1 = array_rand($firstName);
	do {
		$randIndex2 = array_rand($firstName);
	} while ($randIndex1 == $randIndex2);
	$randIndex3 = array_rand($lastName);
	
	if ( $accented ) {
		$fname = 'Zoé Loïc';
		$lname = 'ACUÑA GALVÁN CORTÉS';		
	} else {
		$fname = $firstName[$randIndex1] . ' ' . $firstName[$randIndex2];
		$lname = $lastName[$randIndex3];
	}
	$user_name = $fname . ' ' . $lname;
	
	$user_id = username_exists( $user_name );
    if ( $user_id ) {
		// already exists
		if ( !$accented ) {
			return false;
		} else {
			return true;
		}
	} else {
		// create
		$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = true );
		$emailname = remove_accents($user_name);
		$emailname = str_replace(' ', '', $emailname);
		$user_email = $emailname . '@example.com';
		$user_id = wp_create_user( $user_name, $random_password, $user_email );
		if ( !$user_id ) return false;
		$user_id = wp_update_user( array(
			'ID' => $user_id,
			'user_nicename' => $user_name,
			'display_name' => $user_name,
			'nickname' => $user_name,
			'first_name' => $fname,
			'last_name' => $lname,
			) );
		return $user_id;
	}
	
	return false;
}

function bbpsd_get_cupcake_word( $capitalize = false ) {
	$cupcakes = array( 'apple', 'bar', 'beans', 'bear', 'bears', 'biscuit', 'bonbon', 'brownie', 'cake', 'candy', 'canes', 'caramels', 'carrot', 'cheesecake', 'chocolate', 'chupa chups', 'claw', 'cookie', 'cotton', 'cream', 'croissant', 'cupcake', 'danish', 'dessert', 'donut', 'drag', 'drops', 'fruitcake', 'gingerbread', 'gummi', 'gummies', 'halvah', 'ice', 'icing', 'jelly', 'jelly-o', 'jujubes', 'lemon', 'liquorice', 'lollipop', 'macaroon', 'marshmallow', 'marzipan', 'muffin', 'oat', 'pastry', 'pie', 'plum', 'powder', 'pudding', 'roll', 'sesame', 'snaps', 'soufflé', 'sugar', 'sweet', 'tart', 'tiramisu', 'toffee', 'tootsie', 'topping', 'wafer' );

	$randIndex = array_rand($cupcakes);
	$word = $cupcakes[$randIndex];
	
	if ( $capitalize ) {
		$word = ucfirst( $word );
	}
	
	return $word;
}

function bbpsd_get_cupcake_quote( $nbr_sentences = 2 ) {
	$quote = '';
	$i = 0;
	do {
		$sentence = bbpsd_get_cupcake_word( true ) . ' ';
		$nbr_words = rand(2,10);
		$j = 0;
		do {
			$sentence = $sentence . bbpsd_get_cupcake_word() . ' ';
			$j++;
		} while ( $j < $nbr_words );
		$quote = $quote . trim($sentence) . '. ';
		$i++;
	} while ( $i < $nbr_sentences );
	$quote = trim($quote);
	
	return $quote;
}