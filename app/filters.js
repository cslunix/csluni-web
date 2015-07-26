(function(){
	var app = angular.module('Filters', []);

	app.filter('afectacionSiNo', function(){
		return function(input){
			return input == 1 ? 'Si' : 'No';
		};
	});

	app.filter('pendienteAprobado', function(){
		return function(input){
			return input == 0 ? 'pendiente' : ( (input == 1) ? 'aprobado' : ( (input == 2) ? 'cancelado' :'pendiente') );
		};
	});

	app.filter('tipoReg', function(){
		return function(input){
			//0 programado 1 emergencia 2 coberturado
			return input == 0 ? 'programado' : ( input == 1 ? 'emergencia' : ( input == 2 ? 'coberturado' :'ND') );
		};
	});

	app.filter('statusOM', function(){
		return function(input){
			return input == 0 ? 'En Revision' : ( input == 1 ? 'Aprobado' : ( input == 2 ? 'Rechazado' :'En Revision') );
		};
	});

	app.filter('statusNOC', function(){
		return function(input){
			return input == 0 ? 'No Iniciado' : ( (input == 1) ? 'Iniciado' : ( (input == 2) ? 'Finalizado' : ( input == 3 ? 'Cancelado' : 'En Progreso')) );
		};
	});

	app.filter('resultados', function(){
		return function(input){
			//Cancelado 0 Exitoso 1 Rollback 2 Parcial 3 progreso 4
			return input == null || input == 50 ? '-----' : ( input == 0 ? 'Cancelado' : ( input == 1 ? 'Exitoso' : (input == 2 ? 'Rollback' : (input == 3 ? 'Parcialmente Exitoso' : 'En  Progreso') )) );
		};
	});


	app.filter('servicioAfectado', function(){
		return function(IDsString){
			//ss.split(",")
			var servicios = ["","Conexión Directa","Conexión Directa Internacional","Larga Distancia Nacional","Larga Distancia Internacional","Telefonía Móvil","Roaming Internacional","Navegador Nextel","Mensajería (SMS)","Mensajería (MMS)","Larga Distancia Nacional ","Larga Distancia Internacional","Telefonía Móvil","Roaming Internacional","Navegador Nextel","Mensajería (SMS)","Mensajería (MMS)","Conexión Directa","Conexión Directa Internacional","Larga Distancia Internacional","Larga Distancia Nacional","Telefonía Móvil","Roaming Internacional","Navegador Nextel","Modem Nextel","Mensajería (SMS)","Mensajería (MMS)","Internet Nextel","Larga Distancia Internacional","Larga Distancia Nacional","Telefonía Móvil","Roaming Internacional","Navegador Nextel","Modem Nextel","Mensajería (SMS)","Mensajería (MMS)","Internet Nextel","Acceso a Internet Fijo","Telefonia Fija"];
			var IDsArray = IDsString.split(",");
			var afectados = "";
			var arr = [];
			IDsArray.forEach(function(v){
				//afectados += servicios[v] + ' ';
				arr.push(servicios[v]);
			} )
			return arr;
			//return afectados;
		};
	});

	app.filter('validador', function(){
		return function(validadorID){
			var v = [{id:27, nombre:'Elmer Edgardo Astocondor Sedano'},{id:24, nombre:'Carlo Apagueño'},{id:40, nombre:'Jimy Sirlupu'},{id:46, nombre:'Luis Rivera'},{id:4, nombre:'Dámaris Ancí Paredes'},{id:32, nombre:'Jesus Bazan'},{id:49, nombre:'Andres Rojas Advincula'},{id:50, nombre:'Vladimir Tolentino'},{id:51, nombre:'Luis Paredes Malpartida'},{id:52, nombre:'Mario Lozada Guerrero'},{id:5, nombre:'Javier Acosta Quesada'},{id:26, nombre:'Maria  Jimena Arguello Baltodano'},{id:53, nombre:'Jose Raul Jorquiera Silva'},{id:54, nombre:'Luis Mujica Figueroa'},{id:55, nombre:'Bruno Vivar Linares'},{id:57, nombre:'Dick Leon'},{id:56, nombre:'Wattson Ramirez'},{id:58, nombre:'Elbio Primo Mayo'},{id:59, nombre:'Dremler Polo'},{id:63, nombre:'Fernando Sifuentes Barrientos'},{id:60, nombre:'Luis Torres Figueroa'},{id:61, nombre:'Carlos Zorrilla Calancha'},{id:62, nombre:'Martin Gregorio Rivera Chiong'},{id:66, nombre:'Manuel Eduardo Roman Pilco'},{id:65, nombre:'Julia Palomino'},{id:64, nombre:'Jose Julio Villa Caballero'},{id:67, nombre:'Fredy Mejia Matias'},{id:73, nombre:'Juan Figueroa Lucano'},{id:74, nombre:'John Gamonal Templo'},{id:76, nombre:'Edgar Alex Jara Pedraza'},{id:87, nombre:'Pedro Bernardo Carlos Herrera'},{id:88, nombre:'Yuri Raul Castro Zarate'},{id:89, nombre:'Angel Maldonado Laurente'},{id:90, nombre:'Luis Urbina'},{id:91, nombre:'Luis Miguel Vinelli'},{id:92, nombre:'Robert Zubieta Cardenas'},{id:95, nombre:'Cesar Ramirez'},{id:96, nombre:'Carlos Alberto Cáceda Quispe'},{id:97, nombre:'Francisco Leopoldo Castillo Briceño'},{id:106, nombre:'Marlon De la Cruz Peña'},{id:105, nombre:'Fernando Saavedra'},{id:39, nombre:'Victor Hugo Rodriguez'},{id:123, nombre:'Javier Angel Mendez Sanchez'},{id:33, nombre:'Claudia Luz Bejar Camapaza'},{id:110, nombre:'Edgar Francisco Cano Cordova'},{id:107, nombre:'Percy Cossio Vereau'},{id:109, nombre:'Jesus Gabriel Ly Ponce'},{id:108, nombre:'Rudy Cesar Meza Berrospi'},{id:93, nombre:'Luis Alberto Vasquez Agreda'},{id:25, nombre:'Elmer Aquino'},{id:111, nombre:'Luis Manuel Bernardo Lizarbe'},{id:94, nombre:'Jose Rivera'},{id:82, nombre:'Luis Torres Ampuero'},{id:85, nombre:'Jorge Joel Valladares Bendezu'},{id:69, nombre:'Randol Miguel Zevallos Mendez'},{id:81, nombre:'Miguel Suasnabar'},{id:79, nombre:'Edgar Alexander Piñin Ramirez'},{id:112, nombre:'Ricardo Pedro Castro Alvarez'},{id:113, nombre:'Carlos Javier Coral Lazo'},{id:114, nombre:'Jose Luis Cuya Urbina'},{id:115, nombre:'Raul Esteban Espinoza Saavedra'},{id:116, nombre:'Gonzalo Pedro Estrella Bravo'},{id:117, nombre:'Gianpier Arnaldo Huapaya Herencia'},{id:118, nombre:'Rommel Jerson Salvador Cochachin'},{id:119, nombre:'Enrique Leo Samaniego Vasquez'},{id:17, nombre:'Ronald Abarca'},{id:35, nombre:'James Lenin Calla Bernuy'},{id:120, nombre:'Ezequiel Ernesto Castro Cevallos'},{id:72, nombre:'Igor Cruz'},{id:71, nombre:'Luis Miguel Gonzales Cabanillas'},{id:75, nombre:'Charles Huamanchumo'},{id:77, nombre:'Franklin David Medina Nakamine'},{id:78, nombre:'Marco Antonio Olazabal Mendocilla'},{id:80, nombre:'Cesar Rivas Loayza'},{id:84, nombre:'Omar Urteaga'},{id:70, nombre:'Mijael Rumer Espinoza Benites'},{id:121, nombre:'Percy Javier Rodriguez'},{id:122, nombre:'Jose Luis Huanca Barrantes'},{id:124, nombre:'Luis Felipe Ocampo Rodriguez'},{id:125, nombre:'Alberto Julio Pau Gomez'},{id:126, nombre:'Juan Rolando Pelaez Ortiz'},{id:127, nombre:'Cesar Augusto Piminchumo Venegas'},{id:128, nombre:'Walter Miguel Ramirez Gomez'},{id:129, nombre:'Marcial Rodriguez Pillman'},{id:131, nombre:'Carlos William Torres Morales'},{id:130, nombre:'Rafael Umberto Sanchez Rengifo'},{id:132, nombre:'Milton David Vasquez Campos'},{id:98, nombre:'Walter Del Aguila Moya'},{id:37, nombre:'Giancarlo Paoli'},{id:16, nombre:'Eisson Alipio Rodriguez'},{id:68, nombre:'Gerardo Gallegos Pantoja'},{id:47, nombre:'Henry Chang'},{id:42, nombre:'Luis Martinez'},{id:48, nombre:'Judith Llacza'}];
			var nombre = '';
			for(i in v){
				if(v[i].id == validadorID){
					nombre = v[i].nombre;
					break;
				}
			}
			return nombre;
		};
	});


	app.filter('getTeamString', function(){
		return function(teamID){
			var teams = ["", "REGULATORIO", "GESTION OPERATIVA", "O&M CORE", "VOZ", "CORE VOZ", "CORE RAN", "DATOS", "CORE DATOS", "DATACOMM", "CORE TRANSPORTE", "ISP", "TRANSPORTE Y AGREGACION", "VAS Y PLATAFORMAS", "VAS", "OSS", "O&M SITES", "ADMINISTRACION DE INFRAESTRUCTURA", "OPERACIONES RAN", "SITES CRM", "ACCESO TRANSPORTE"];
			return teams[teamID];
		};
	});

	app.filter('gerencia', function(){
		return function(gerenciaID){
			var gerencias = ["","ASEGURAMIENTO DE LA CALIDAD Y NOC","CONSTRUCCION E INFRAESTRUCTURA DE RED","INGENIERIA DE REDES CORE Y SERVICIOS","INGENIERIA DE RED DE ACCESOS","O&M SITIOS Y ACCESO RED","PLANIFICACION Y CONTROL DE GESTION","O&M REDES CORE TRANSPORTE Y PLATAFORMAS","PMO DE RED","VP", "CONTABILIDAD"];
			return gerencias[gerenciaID];
		};
	});

	app.filter('nocString', function(){
		return function(nocID){
			var islas = ["","Datacomm", "Dispatch", "Nodos-TX", "Telefonia", "VAS"];
			return islas[nocID];
		};
	});

	app.filter('personalType', function(){
	return function(typeID){
		var strPersonal = ['Ejecutor','POC','Soporte','BabySitting', 'POC Llaves'];
			return strPersonal[typeID];
		};
	});

	app.filter('rpnizate', function(){
		return function(rpn){
			// 0 199 bajo
			// 200 599 medio
			// 600 999 alto
			// 1000 mas critico
			var riesgo = '';
			if(rpn == null){
				riesgo = 'Loading';
			}
			if(rpn <= 199){
				riesgo = 'Bajo';
			}
			if(rpn > 199 && rpn < 600){
				riesgo = 'Medio';
			}

			if(rpn > 599 && rpn < 1000){
				riesgo = 'Alto';
			}

			if(rpn > 999){
				riesgo = 'Critico';
			}
			return riesgo;
		};
	});

	app.filter('tiempoAfectacion', function(){
		return function(tiempo_segundos){
			if(tiempo_segundos){
				var horas    = parseInt(tiempo_segundos/3600);
				var minutos  = parseInt((tiempo_segundos-3600*horas)/60);
				var segundos = tiempo_segundos - 3600*horas - minutos*60;
				if(segundos == 0){
					var resultado = horas.toString() +'Hrs ' + minutos.toString() + 'min ';
				} else {
					var resultado = horas.toString() +'Hrs ' + minutos.toString() + 'min '+ segundos.toString() +'seg. ';
				}

				if(segundos == 0 && minutos == 0){
					var resultado = horas.toString() +'Hrs ';
				} else {
					var resultado = horas.toString() +'Hrs ' + minutos.toString() + 'min '+ segundos.toString() +'seg. ';
				}
				//var resultado = horas.toString() +'Hrs ' + minutos.toString() + 'min '+ segundos.toString() +'seg. ';
				return resultado;
			} else{
				return 'No se indico';
			}
		};
	});

})();