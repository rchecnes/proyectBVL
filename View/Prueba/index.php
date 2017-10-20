<script type="text/javascript">
	function cargarHcotizacion(a){
		
		A.one(".his-cotizacion_div").loadingmask.toggle();	
		var mesini = A.one("#_informaciongeneral_WAR_servicesbvlportlet_mesIni").get('value');
		var anoini = A.one("#_informaciongeneral_WAR_servicesbvlportlet_anoIni").get('value');
		var mesfin = A.one("#_informaciongeneral_WAR_servicesbvlportlet_mesFin").get('value');
		var anofin = A.one("#_informaciongeneral_WAR_servicesbvlportlet_anoFin").get('value');
		var nemonico = a;//A.one("#_informaciongeneral_WAR_servicesbvlportlet_valor_resumen").get('value');
	
		
		A.io.request('https://www.bvl.com.pe:443/web/guest/informacion-general-empresa?p_p_id=informaciongeneral_WAR_servicesbvlportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-2&p_p_col_count=1&_informaciongeneral_WAR_servicesbvlportlet_cmd=getListaHistoricoCotizaciones&_informaciongeneral_WAR_servicesbvlportlet_codigoempresa=60800&_informaciongeneral_WAR_servicesbvlportlet_nemonico=ATACOBC1&_informaciongeneral_WAR_servicesbvlportlet_tabindex=4&_informaciongeneral_WAR_servicesbvlportlet_jspPage=%2Fhtml%2Finformaciongeneral%2Fview.jsp', {
			method : 'POST',
			dataType: 'json',
			data : {
				_informaciongeneral_WAR_servicesbvlportlet_anoini: anoini,
				_informaciongeneral_WAR_servicesbvlportlet_mesini: mesini,
				_informaciongeneral_WAR_servicesbvlportlet_anofin: anofin,
				_informaciongeneral_WAR_servicesbvlportlet_mesfin: mesfin,
				_informaciongeneral_WAR_servicesbvlportlet_nemonicoselect: nemonico
			},
			on : {
				success : function() {
					A.one(".his-cotizacion_div").loadingmask.hide();	
					var jsonResponse = this.get('responseData');
					
					if(jsonResponse.errorMessage) {
						alert(jsonResponse.errorMessage);
					} else {
						
						var data = jsonResponse.data;
						var table = A.one("#historicocotizacionesTable tbody");
						table.empty();
						if(data.length <= 0){
							var tr = A.Node.create("<tr></tr>");
							tr.html("<td colspan='10' >\u004e\u006f\u0020\u0068\u0061\u0079\u0020\u0069\u006e\u0066\u006f\u0072\u006d\u0061\u0063\u0069\u00f3\u006e\u0020\u0070\u0061\u0072\u0061\u0020\u006d\u006f\u0073\u0074\u0072\u0061\u0072\u0020\u0063\u006f\u006e\u0020\u006c\u006f\u0073\u0020\u0063\u0072\u0069\u0074\u0065\u0072\u0069\u006f\u0073\u0020\u0064\u0065\u0020\u0062\u00fa\u0073\u0071\u0075\u0065\u0064\u0061</td>");
							table.append(tr);
						}
						A.each(data, function(obj, it) {
							
							var df = new DecimalFormat("#,##0.00");
							
							var promedio
							if(obj.valAmt > 0 && obj.valVol> 0){ 
								promedio=  (obj.valAmt/obj.valVol) ;
								promedio=  df.format(Number(promedio).toFixed(2));
							}else{
								promedio= ""; 
							}
							
							//df.format(Number(obj.precio).toFixed(2))
							var num = Number(obj.valPts);
							var numer = df.format(obj.valPts);

							var tr = A.Node.create("<tr></tr>");
							
							tr.html("<td style='text-align: center;'>" + validateNull(obj.fecDt) + "</td>" +
									"<td style='text-align: right;'>" + validateNull(df.format(obj.valOpen)) + "</td>" +
									"<td style='text-align: right;'>" + validateNull(df.format(obj.valLasts)) + "</td>" +
									"<td style='text-align: right;'>" + validateNull(df.format(obj.valHighs)) + "</td>" +
									"<td style='text-align: right;'>" + validateNull(df.format(obj.valLows)) + "</td>" +
									"<td style='text-align: right;'>" + validateNull(promedio) +"</td>" +
									
									"<td style='text-align: center;'>" + validateNull(df.format(obj.valVol)) +"</td>" +
									"<td style='text-align: right;'>" + validateNull(df.format(obj.valAmtSol)) +"</td>" +
									"<td style='text-align: center;border-left: 1px solid rgba(102, 102, 102, 0.25)'>" + validateNull(obj.fecTimp) + "</td>" +
									"<td style='text-align: right;border-right: 1px solid rgba(102, 102, 102, 0.25)' >" + validateNull(df.format(obj.valPts)) +"</td>");
							
							table.append(tr);
						});
					}
				}
			}
			});
	}

</script>